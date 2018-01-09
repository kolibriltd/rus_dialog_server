<?php

Class Api extends ApiBase {
    
    public static function list_books() {
       /* static::_check_auth();
        static::_revizion_code();*/
        
        $listBooks = Books::model()->findAll("status=0");
	$resp = "";
	if ($listBooks != null) {
            foreach ($listBooks as $item) {
                $txt = json_decode($item->path_txt_file);
                $resp[] = array(
                    "id_book" => $item->id_book,
                    "name" => $item->name,
                    "author" => $item->author,
                    "is_view_count" => $item->is_view_count,
                    "raiting" => $item->raiting,
                    "description" => $item->description,
                    "text" => $txt->result,
                    "path_cover_file" => $item->path_cover_file,
                    "type_id" => $item->type_id,
                    "last_modified" => $item->last_modified,
                );
            }
	}
        static::_send_resp($resp);
    }
    
    public static function guest_books() {
        $itemBooks = Books::model()->find("guest=:guest", array(":guest"=>1));
        $txt = json_decode($itemBooks->path_txt_file);
        //print_r($txt);
        $resp = array(
            "id_book" => $itemBooks->id_book,
            "name" => $itemBooks->name,
            "author" => $itemBooks->author,
            "is_view_count" => $itemBooks->is_view_count,
            "raiting" => $itemBooks->raiting,
            "description" => $itemBooks->description,
            "path_cover_file" => $itemBooks->path_cover_file,
            "text" => $txt->result,
            "type_id" => $itemBooks->type_id,
            "last_modified" => $itemBooks->last_modified,
        );
       
        static::_send_resp($resp);
    }
    
    public static function descript_book() {
        /*if (isset($_GET["flag"]) && $_GET["flag"] == "true") {
            static::_check_auth();
        }*/
        $oneBook = Books::model()->find("id_book=:id_book", array(":id_book" => $_GET['id']));
        $resp = "";
        if ($oneBook != null) {
            $txt = json_decode($oneBook->path_txt_file);
            $resp = array(
                "id_book" => $oneBook->id_book,
                "name" => $oneBook->name,
                "author" => $oneBook->author,
                "description" => $oneBook->description,
                "path_cover_file" => $oneBook->path_cover_file,
                "text" => $txt->result,
                "type_id" => $oneBook->type_id,
            );
        }
        static::_send_resp($resp);
    }
    
    public static function view_inc_count() {
        //static::_check_auth();
        $oneBook = Books::model()->find("id_book=:id_book", array(":id_book" => $_GET['id']));
        $resp = null;
        if ($oneBook != null) {
            $oneBook->is_view_count = $oneBook->is_view_count + 1;
            
            if ($oneBook->save()) {         
                $resp = array(
                    "id_book" => $oneBook->id_book,
                    "name" => $oneBook->name,
                    "author" => $oneBook->author,
                    "is_view_count" => $oneBook->is_view_count,
                    "type_id" => $oneBook->type_id,
                );
            }
            else {
                $resp = array(
                    "id_book" => $oneBook->id_book,
                    "name" => $oneBook->name,
                    "author" => $oneBook->author,
                    "is_view_count" => $oneBook->is_view_count,
                    "type_id" => $oneBook->type_id,
                );
            }
        }
        static::_send_resp($resp);
    }
    
    public static function get_view_inc_count() {
       // static::_check_auth();
        $listBooks = Books::model()->findAll();
        $resp = "";
	if ($listBooks != null) {
            foreach ($listBooks as $item) {
                $resp[] = array(
                    "id_book" => $item->id_book,
                    "name" => $item->name,
                    "author" => $item->author,
                    "raiting" => $item->raiting,
                    "is_view_count" => $item->is_view_count,
                    "type_id" => $item->type_id,
                );
            }
	}
        static::_send_resp($resp);
    }
    
    public static function set_raiting_book() {
        //static::_check_auth();
        $oneBook = Books::model()->find("id_book=:id_book", array(":id_book" => $_GET['id']));
        $resp = null;
        if ($oneBook != null) {
            $oneBook->raiting_count = $oneBook->raiting_count + 1;
            $oneBook->raiting_sum = $oneBook->raiting_sum + $_GET["rating"];
            $oneBook->raiting = number_format($oneBook->raiting_sum / $oneBook->raiting_count, 1, '.', ',');
            
            if ($oneBook->save()) {         
                $resp = array(
                    "id_book" => $oneBook->id_book,
                    "name" => $oneBook->name,
                    "author" => $oneBook->author,
                    "raiting" => $oneBook->raiting,
                    "is_view_count" => $oneBook->is_view_count,
                    "type_id" => $oneBook->type_id,
                );
            }
            else {
                $resp = array(
                    "id_book" => $oneBook->id_book,
                    "name" => $oneBook->name,
                    "author" => $oneBook->author,
                    "raiting" => $oneBook->raiting,
                    "is_view_count" => $oneBook->is_view_count,
                    "type_id" => $oneBook->type_id,
                );
            }
        }
        static::_send_resp($resp);
    }
}
