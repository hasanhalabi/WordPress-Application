<?php

/**
 * Objet Type: usergroups
 */
class usergroups extends jomiz_api_object_type {
	public function get_object( $id ) {
		$formData = new stdClass ();
		$objData  = new stdClass ();
		
		// Get Object Data
		if ( $id == - 1 ) {
			$objData->id               = - 1;
			$objData->name             = '';
			$objData->members          = array();
			$objData->managers         = array();
			$objData->privileges       = new stdClass ();
			$objData->reports          = new stdClass ();
			$objData->widgets          = new stdClass ();
			$objData->recordOwner      = '-';
			$objData->recordCreatedOn  = '-';
			$objData->recordModifiedOn = '-';
		} else {
			$jomiz_usergroup = pods( 'jomiz_usergroup', $id );
			
			$objData->id               = $id;
			$objData->name             = $jomiz_usergroup->field( 'name' );
			$objData->members          = array();
			$objData->managers         = array();
			$objData->privileges       = new stdClass ();
			$objData->reports          = new stdClass ();
			$objData->widgets          = new stdClass ();
			$objData->recordOwner      = $jomiz_usergroup->field( 'author.display_name' );
			$objData->recordCreatedOn  = $jomiz_usergroup->field( 'created' );
			$objData->recordModifiedOn = $jomiz_usergroup->field( 'modified' );
			
			$members = $jomiz_usergroup->field( 'members' );
			
			foreach ( $members as $member ) {
				$objData->members [] = ( object ) array(
					'userId'   => $member ['ID'],
					'username' => $member ['display_name']
				);
			}
			
			$managers = $jomiz_usergroup->field( 'managers' );
			
			if ( $managers !== false ) {
				foreach ( $managers as $manager ) {
					$objData->managers [] = ( object ) array(
						'userId'   => $manager ['ID'],
						'username' => $manager ['display_name']
					);
				}
			}
			
			$privileges = json_decode( $jomiz_usergroup->field( 'privileges' ) );
			
			if ( isset ( $privileges->capabilities ) ) {
				foreach ( $privileges->capabilities as $capability ) {
					$objData->privileges->{$capability} = true;
				}
			}
			
			if ( isset ( $privileges->reports ) ) {
				foreach ( $privileges->reports as $report ) {
					$objData->reports->{$report} = true;
				}
			}
			
			if ( isset ( $privileges->widgets ) ) {
				foreach ( $privileges->widgets as $widget ) {
					$objData->widgets->{$widget} = true;
				}
			}
		}
		
		// Get Form Data
		// Users
		
		$users        = array();
		$system_users = get_users();
		foreach ( $system_users as $user ) {
			$users [] = ( object ) array(
				'userId'   => $user->ID,
				'username' => $user->display_name
			);
		}
		$plugins_components = array();
		
		$params             = array();
		$params ['limit']   = - 1;
		$params ['orderby'] = 'display_order';
		$params['where']    = "plugin_code <> 'plugins-manager'";
		
		$registerdPlugins = pods( 'jomiz_registeredplugin', $params );
		
		while ( $registerdPlugins->fetch() ) {
			
			$configuration          = json_decode( $registerdPlugins->field( 'configuration' ) );
			$name                   = $configuration->plugin_info->name;
			$plugin_code            = $registerdPlugins->field( 'plugin_code' );
			$plugin_language_domain = $configuration->plugin_info->language_domain;
			$plugin_icon            = $configuration->plugin_info->icon;
			
			$pluginInfo          = new stdClass ();
			$pluginInfo->id      = $registerdPlugins->field( 'id' );
			$pluginInfo->name    = __( $name, $plugin_language_domain );
			$plugin_capabilities = array();
			
			foreach ( $configuration->priviliges as $object_config ) {
				$capabilities = array_map( function ( $plugin_code, $object_code, $language_domain, $cap ) {
					
					if ( in_array( $cap, array(
						'add',
						'edit',
						'menu-item',
						'delete',
						'view-all-data',
						'view-data-of-team',
						'print'
					) ) ) {
						$language_domain = 'jomizsystem';
					}
					
					return ( object ) array(
						"code"    => sprintf( '%1$s-%2$s-%3$s', $plugin_code, $object_code, $cap ),
						"display" => __( $cap, $language_domain )
					);
				},
					// Plugin Code
					array_fill( 0, sizeof( $object_config->roles ), $plugin_code ),
					// Object Name
					array_fill( 0, sizeof( $object_config->roles ), $object_config->object ),
					// Language Domain
					array_fill( 0, sizeof( $object_config->roles ), $plugin_language_domain ),
					// The Roles Of The Object
					( array ) $object_config->roles );
				
				if ( sizeof( $capabilities ) > 0 ) {
					$plugin_obj               = new stdClass ();
					$plugin_obj->title        = __( $object_config->title, $plugin_language_domain );
					$plugin_obj->capabilities = $capabilities;
					$plugin_capabilities []   = $plugin_obj;
				}
			}
			
			// Reports
			$pluginInfo->reports = array();
			
			if ( sizeof( $configuration->reports ) > 0 ) {
				$pluginInfo->reports = array_map( function ( $plugin_code, $language_domain, $report ) {
					return ( object ) array(
						"code"    => sprintf( '%1$s-%2$s-%3$s', $plugin_code, 'report', $report->permalink ),
						"display" => __( $report->title, $language_domain )
					);
				},
					// Plugin Code
					array_fill( 0, sizeof( $configuration->reports ), $plugin_code ),
					// Language Domain
					array_fill( 0, sizeof( $configuration->reports ), $plugin_language_domain ),
					// Reports
					$configuration->reports );
			}
			
			// Widgets
			$pluginInfo->widgets = array();
			if ( sizeof( $configuration->widgets ) > 0 ) {
				$pluginInfo->widgets = array_map( function ( $plugin_code, $language_domain, $widget ) {
					return ( object ) array(
						"code"    => sprintf( '%1$s-%2$s-%3$s', $plugin_code, 'widget', $widget->permalink ),
						"display" => __( $widget->title, $language_domain )
					);
				},
					// Plugin Code
					array_fill( 0, sizeof( $configuration->widgets ), $plugin_code ),
					// Language Domain
					array_fill( 0, sizeof( $configuration->widgets ), $plugin_language_domain ),
					// Reports
					$configuration->widgets );
			}
			
			$pluginInfo->capabilities = $plugin_capabilities;
			
			$plugins_components [] = $pluginInfo;
		}
		
		$formData->users              = $users;
		$formData->plugins_components = $plugins_components;
		
		$data           = new stdClass ();
		$data->objData  = $objData;
		$data->formData = $formData;
		
		return $data;
	}
	
	public function save_object( $objData ) {
		
		// Convert $objData into array
		$dataToSave = json_decode( json_encode( $objData ), true );
		
		// Convert Memebers into array of IDs
		$members = array();
		foreach ( $dataToSave ['members'] as $user ) {
			$members [] = $user ['userId'];
		}
		$dataToSave ['members'] = $members;
		
		// Convert Managers into array of IDs
		$managers = array();
		
		foreach ( $dataToSave ['managers'] as $user ) {
			$managers [] = $user ['userId'];
		}
		
		$dataToSave ['managers'] = $managers;
		
		// Convert Managers into array of IDs
		$privileges = ( object ) array(
			'capabilities' => array(),
			'reports'      => array(),
			'widgets'      => array()
		);
		foreach ( $dataToSave ['privileges'] as $capability => $status ) {
			if ( $status ) {
				$privileges->capabilities [] = $capability;
			}
		}
		foreach ( $dataToSave ['reports'] as $report => $status ) {
			if ( $status ) {
				$privileges->reports [] = $report;
			}
		}
		foreach ( $dataToSave ['widgets'] as $widget => $status ) {
			if ( $status ) {
				$privileges->widgets [] = $widget;
			}
		}
		$dataToSave ['privileges'] = json_encode( $privileges );
		
		if ( $dataToSave ['id'] < 0 ) {
			$dataToSave ['id'] = pods( 'jomiz_usergroup' )->add( $dataToSave );
		} else {
			pods( 'jomiz_usergroup', $dataToSave ['id'] )->save( $dataToSave );
		}
		
		$data = $this->get_object( $dataToSave ['id'] );
		
		return $data;
	}
	
	public function delete_object( $objData ) {
		return pods( 'jomiz_usergroup', $objData->id )->delete();
	}
	
	public function list_objects( $operationData ) {
		$params             = array();
		$params ['orderby'] = ( empty ( $operationData->orderby ) ? 'name ASC, modified desc' : $operationData->orderby );
		$params ['offset']  = ( $operationData->pageToFetch - 1 ) * $operationData->pageSize;
		$params ['limit']   = $operationData->pageSize;
		
		if ( ! empty ( $operationData->filter->name ) ) {
			$name             = $operationData->filter->name;
			$params ['where'] = "name like '%$name%'";
		}
		
		$records = pods( 'jomiz_usergroup', $params );
		
		$data = new stdClass ();
		
		$data->pagingInfo               = new stdClass ();
		$data->pagingInfo->currentPage  = $operationData->page_to_fetch;
		$data->pagingInfo->totalRecords = $records->total_found();
		$data->pagingInfo->totalPages   = ( ( $operationData->pageSize == 0 || ( $records->total_found() / $operationData->pageSize ) < 1 ) ? 1 : $records->total_found() / $operationData->pageSize );
		
		$data->objectsList = array();
		while ( $records->fetch() ) {
			$object       = new stdClass ();
			$object->id   = $records->field( 'id' );
			$object->name = $records->field( 'name' );
			
			$data->objectsList [] = $object;
		}
		
		return $data;
	}
	
	public function delete_objects_list( $operationData ) {
		$jomiz_usergroup_pod = pods( 'jomiz_usergroup' );
		
		foreach ( $operationData->objectsToDelete as $id ) {
			$jomiz_usergroup_pod->delete( $id );
		}
		
		return true;
	}
}

?>