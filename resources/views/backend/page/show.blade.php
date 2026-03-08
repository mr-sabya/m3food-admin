@extends('backend.layouts.app')

@section('content')
<livewire:page.show pageId="{{ $page->id }}" />
@endsection