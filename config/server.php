<?php

const SERVER = "localhost";
const DB = "appweb";
const USER = "root";
const PASS = "";

const SGBD = "mysql:host=".SERVER.";dbname=".DB;

#para la encriptacion
const METHOD = "AES-256-CBC";
const SECRET_KEY = '$appweb@2024'; #cuando se haga un registro ya no se podra cambiar
const SECRET_IV='2024'; #cuando se haga un registro ya no se podra cambiar

