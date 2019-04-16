<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class SiteController extends Controller
{
    public function index()
    {
    	return 'Home Page';
    }
     public function janeiro()
    {
    	return view('site.janeiro');
    }
    public function fevereiro()
    {
        $lista = array();
    	return view('site.fevereiro', compact("lista"));
    }
    public function marco()
    {
    	return view('site.marco');
    }
    public function abril()
    {
    	return view('site.abril');
    }
    public function maio()
    {
    	return view('site.maio');
    }
    public function junho()
    {
    	return view('site.junho');
    }
    public function julho()
    {
    	return view('site.julho');
    }
    public function agosto()
    {
    	return view('site.agosto');
    }
    public function setembro()
    {
    	return view('site.setembro');
    }
    public function outubro()
    {
    	return view('site.outubro');
    }
    public function novembro()
    {
    	return view('site.novembro');
    }
    public function dezembro()
    {
    	return view('site.dezembro');
    }
}