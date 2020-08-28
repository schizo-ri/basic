@extends('errors::minimal')

@section('title', __('U radu---'))
@section('code', '503')
@section('message', __($exception->getMessage() ?: 'Stranica trenutno nije dostupna, molim pokušajte kasnije.'))
