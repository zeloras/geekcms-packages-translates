@extends('admin.layouts.main')

@section('title', \Translate::get('module_translates::admin/sidenav.name'))

@section('content')

<script>
    var langIndexUrl = '{{ route('admin.translates.list', ['page' => null]) }}';
</script>

@php($i = 2)
<section class="box-typical container pb-3">
    <header class="box-typical-header">
        <div class="tbl-row">
            <div class="tbl-cell tbl-cell-title border-bottom">
                <form class="form-inline">
                    <label class="mr-sm-2" for="languages_select">
                        {{ \Translate::get('module_translates::admin/main.language_select') }}:
                    </label>

                    <select class="form-control mb-2 mr-sm-2 mb-sm-0 languages_select_list" id="languages_select">

                        @foreach($languages as $element)
                            @php($selected = ($current_language && $element->id == $current_language->id) ? 'selected' : '')

                            <option value="{{ $element->id }}" {{$selected}}>
                                {{ $element->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </header>
    <div class="box-typical-body">
        <div class="table-responsive">
            <form action="{{ route('admin.translates.save', ['lang' => $current_language->id]) }}" method="POST">
                @csrf
                <section class="tabs-section tab-section__no-border">
                    <div class="tabs-section-nav">
                        <div class="tbl">
                            <ul class="nav translates-nav" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active show" href="#tabs-2-tab-1" role="tab" data-toggle="tab" aria-selected="true">
                                    <span class="nav-link-in">
                                        {{ \Translate::get('module_translates::admin/main.main_translates') }}
                                    </span>
                                    </a>
                                </li>
                                @foreach ($modules as $module)
                                    @php ($module_short = \Gcms::MODULES_PREFIX.strtolower($module->name))
                                    @if (isset($translates_keys->get($current_language->key)[$module_short]))
                                        <li class="nav-item">
                                            <a class="nav-link" href="#tabs-2-tab-{{$i}}" role="tab" data-toggle="tab" aria-selected="false">
                                            <span class="nav-link-in">
                                                {{ \Translate::get('module_translates::admin/main.module') }}: {{ $module->name }}
                                            </span>
                                            </a>
                                        </li>
                                    @endif
                                    @php($i++)
                                @endforeach
                            </ul>
                        </div>
                    </div><!--.tabs-section-nav-->

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active show" id="tabs-2-tab-1">
                            <div class="row">
                            @foreach ($translates_keys->get($current_language->key)['main'] as $main_key => $main_value)
                                @foreach ($main_value as $mkey => $mval)
                                    @php ($input_key = $main_key . '.' . $mkey)
                                    @php ($input_value = $translates[$current_language->key][$input_key])
                                    @php ($input_value = (is_array($input_value)) ? implode(', ', $input_value) : $input_value)
                                    <div class="form-group col-6">
                                        <label for="{{$input_key}}" class="module-languages-label" data-toggle="tooltip" data-placement="left" data-original-title="{{ $input_key }}">{{$input_key}}:</label>
                                        <input class="form-control" id="{{$input_key}}" name="translates[{{$input_key}}]" value="{{ $input_value ?? old('name') }}">
                                    </div>
                                @endforeach
                            @endforeach
                            </div>
                        </div><!--.tab-pane-->
                        @php($i = 2)
                        @foreach ($modules as $module)
                            <div role="tabpanel" class="tab-pane fade" id="tabs-2-tab-{{$i}}">
                                <div class="row">
                                    @php ($module_short = \Gcms::MODULES_PREFIX.strtolower($module->name))
                                    @if (isset($translates_keys->get($current_language->key)[$module_short]))
                                        @foreach ($translates_keys->get($current_language->key)[$module_short] as $main_key => $main_value)
                                            @foreach ($main_value as $mkey => $mval)
                                                @php ($input_key = $module_short . '::' . $main_key . '.' . $mkey)
                                                @php ($input_value = $translates[$current_language->key][$input_key])
                                                @php ($input_value = (is_array($input_value)) ? implode(', ', $input_value) : $input_value)
                                                <div class="form-group col-6">
                                                    <label for="{{$input_key}}" class="module-languages-label" data-toggle="tooltip" data-placement="left" data-original-title="{{ $input_key }}">{{$input_key}}:</label>
                                                    <input class="form-control" id="{{$input_key}}" name="translates[{{$input_key}}]" value="{{ $input_value ?? old('name') }}">
                                                </div>
                                            @endforeach
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            @php($i++)
                        @endforeach
                    </div><!--.tab-content-->

                    <div class="form-group text-center pt-3">
                        <button type="submit" class="btn btn-inline btn-success btn-lg">{{\Translate::get('module_translates::admin/main.action_save')}}</button>
                    </div>
                </section>
            </form>
        </div>
    </div>
</section>

@endsection