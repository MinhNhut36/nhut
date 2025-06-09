@extends('layouts.student')
@section('title', 'Chi tiết khóa học')

@section('styles')
@endsection

@section('content')
<h1>{{$course->course_name}}</h1>
<h1>{{$course->lesson->title??"no data"}}</h1>
<h1>{{$course->lesson->level ??"no data"}}</h1>
<h1>{{$course->lesson->description ?? "no data"}}</h1>
@endsection