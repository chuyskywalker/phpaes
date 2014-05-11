<?php

interface Encryption {
    public function encrypt($data);
    public function decrypt($data);
}