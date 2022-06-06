<?php

class lc_licensing {

    public function lc_check_activation_key($param) {
        $url = "https://api.wptools.io/backup/lc_irada_license/lc_irada_accounting_check_key/";
        $data=(object)array();
        $data->key = $param;
        $params = $data;




        $content = '';


        if (is_object($params)) {
            $params = (array) $params;

            foreach ($params as $key => $value) {

                $content .= sprintf('%1$s=%2$s&', $key, $value);
            }
        }

        $options = array(
            'http' => array(
                'header' => "Content-Type: application/x-www-form-urlencoded",
                'method' => 'POST',
                'content' => $content,
            )
        );

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);


        $results = json_decode($result);
        if (isset($results->error)) {
            return(object) array(
                        results => 'not-ok',
                        reason => $results->error_reason
            );
        }
        $data = json_encode($results->data);
        add_option("lc_irada_acticvation_key", $data);
        return (object) array(
                         results => 'ok'
        );
    }

}
