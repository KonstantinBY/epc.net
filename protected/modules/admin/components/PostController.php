<?php
class PostController extends Controller
{
    public function actions()
    {
        return array(
            'fileUpload'=>'ext.redactor.actions.FileUpload',
            'imageUpload'=>'ext.redactor.actions.ImageUpload',
            'imageList'=>'ext.redactor.actions.ImageList',
        );
    }
}