<?php
date_default_timezone_set('Europe/Paris');

/**
 * Upvid Link
 * 
 * @author UpVid <upvid.co@gmail.com>
 */

class UpvidLink {
    private $key;
    private $code;
    
    /**
     * @method construct
     * @param string $key
     * @param string $code
     * @return void
     */
    public function __construct($key, $code) {
        $this->key = preg_match('/^\w+$/', $key) ? $key : false;
        $this->code = preg_match('/^\w{12}$/', $code) ? $code : false;
    }
    
    /**
     * @method link
     * @return string
     */
    public function link() {        
        return 'https://upvid.co/' . $this->encode();
    }
    
    /**
     * @method encode
     * @access private
     * @return string
     */
    private function encode() {
        if($this->key && $this->code){
            list($key, $value) = $this->serial();
            $code = rand(11111111,99999999);
            $code .= '-' . $this->hash();
            $code .= '-' . date('Ymdhs');
            $s = array();
            for ($i = 0; $i < 256; $i++) {
                $s[$i] = $i;
            }
            $j = 0;
            for ($i = 0; $i < 256; $i++) {
                $j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
                $x = $s[$i];
                $s[$i] = $s[$j];
                $s[$j] = $x;
            }
            $i = 0;
            $j = 0;
            $res = '';
            
            for ($y = 0; $y < strlen($code); $y++) {
                $i = ($i + 1) % 256;
                $j = ($j + $s[$i]) % 256;
                $x = $s[$i];
                $s[$i] = $s[$j];
                $s[$j] = $x;
                $res .= $code[$y] ^ chr($s[($s[$i] + $s[$j]) % 256]);
            }
            $encode = base64_encode(base64_encode($res));
            
            $result = str_replace('=', '', $encode);
            $result .= rand(0,9);
            $result .= $value;
            
            return $result;
        }
    }
    
    /**
     * @method serial
     * @access private
     * @return array
     */
    private function serial() {
        preg_match('/^(\w{32})(\w+)$/', $this->key, $match);
        
        $key = $match[1];
        
        $chars = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
        $value = str_replace($chars, '', $match[2]);
        
        $array = array(
            array('a', 'k', 'A', 'K'),
            array('b', 'l', 'B', 'L'),
            array('c', 'm', 'C', 'M'),
            array('d', 'n', 'D', 'N'),
            array('e', 'o', 'E', 'O'),
            array('f', 'p', 'F', 'P'),
            array('g', 'q', 'G', 'Q'),
            array('h', 'r', 'H', 'R'),
            array('i', 's', 'I', 'S'),
            array('j', 't', 'J', 'T')
        );        
        
        $string = 'uvwxyzUVWXYZ';
        
        $serial = false;
        
        for($i=0; $i<strlen($value); $i++){
            if(!$serial){
                $num = rand(0,3);
                $serial = $array[$value[$i]][$num];
            }else{
                $num = rand(0,11);
                $serial .= $string[$num];                
                $num = rand(0,3);
                $serial .= $array[$value[$i]][$num];
            }
        }
        
        return array($key, $serial);        
    }
    
    /**
     * @method hash
     * @access private
     * @return string
     */
    private function hash() {
        $chars = array('&', '|', '!', '@', '#');
        
        $code = false;
        
        for($i=0; $i<strlen($this->code); $i++){
            if(!$code){
                $code = $this->code[$i];
            }else{
                $num = rand(0,4);
                $code .= $chars[$num];
                $code .= $this->code[$i];
            }
        }
        
        return $code;
    }
}
