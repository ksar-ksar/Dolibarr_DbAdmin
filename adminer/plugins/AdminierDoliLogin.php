<?php
/* Copyright (C) 2020       ksar    <ksar.ksar@gmail.com>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * 	\file       AdminerDoliLogin.php
 *	\brief      File that allow autologin to the database
 */
 
class AdminerDoliLogin {
	/** @access protected */
    private $doli_db_host;
	private $doli_db_port;
	private $doli_db_name;
	private $doli_db_user;
	private $doli_db_pass;
	
    public function __construct() {
        global $dolibarr_main_db_host,$dolibarr_main_db_port,$dolibarr_main_db_name,$dolibarr_main_db_user,$dolibarr_main_db_pass ;
		$this->doli_db_host = $dolibarr_main_db_host;
		$this->doli_db_port = $dolibarr_main_db_port;
		$this->doli_db_name = $dolibarr_main_db_name;
		$this->doli_db_user = $dolibarr_main_db_user;
		$this->doli_db_pass = $dolibarr_main_db_pass;
        //set_session('pwds', $dolibarr_main_db_pass);
    }

    public function credentials() {
        if(!empty($this->doli_db_port)) {
            return array($this->doli_db_host.':'.$this->doli_db_port,
                         $this->doli_db_user,
                         $this->doli_db_pass);
        } else {
            return array($this->doli_db_host, $this->doli_db_user, $this->doli_db_pass);
        }
    }

    /*public function loginForm() {
        echo '<div><h3>Not in the tool</h3></div>';
        die();
    }
	
	public function database() {
		// database name, will be escaped by Adminer
		return $this->doli_db_name;
	}*/

	function login($login, $password) {
        return true;
    }
}