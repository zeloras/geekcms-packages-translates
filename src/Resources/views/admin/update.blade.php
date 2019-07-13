@extends('admin.layouts.main')

@section('title', Translate::get('module_translates::admin/main.language_action') )

@section('content')
    <section class="box-typical">
        <header class="box-typical-header">
            <div class="tbl-row">
                <div class="tbl-cell tbl-cell-title border-bottom">
                    <h3>{{ Translate::get('module_translates::admin/main.language_action') }}</h3>
                </div>
            </div>
        </header>
        <div class="box-typical-body pt-3 pb-3">
            <div class="table-responsive container">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ $route }}" method="POST">
                            @csrf
                            @if (isset($item) && $item)
                                <input type="hidden" name="lang_id" value="{{$item->id}}">
                            @endif
                            <div class="row">
                                <div class="form-group col-6">
                                    <label for="name">{{ Translate::get('module_translates::admin/main.name') }}
                                        :</label>
                                    <input class="form-control" id="name" name="name"
                                           value="{{ $item->name ?? old('name') }}" required>
                                </div>

                                <div class="form-group col-6">
                                    <label for="key">{{ Translate::get('module_translates::admin/main.code') }}
                                        :</label>
                                    <input class="form-control" id="key" name="key"
                                           value="{{ $item->key ?? old('key') }}" required>
                                </div>

                                <div class="form-group col-6">
                                    <label for="native">{{ Translate::get('module_translates::admin/main.native') }}
                                        :</label>
                                    <input class="form-control" id="native" name="native"
                                           value="{{ $item->native ?? old('native') }}" required>
                                </div>

                                <div class="form-group col-6">
                                    <label for="script">{{ Translate::get('module_translates::admin/main.script') }}
                                        :</label>
                                    <input class="form-control" id="script" name="script"
                                           value="{{ $item->script ?? old('script') }}" required>
                                </div>

                                <div class="form-group col-6">
                                    <label for="regional">{{ Translate::get('module_translates::admin/main.regional') }}
                                        :</label>
                                    <input class="form-control" id="regional" name="regional"
                                           value="{{ $item->regional ?? old('regional') }}" required>
                                </div>

                                <div class="form-group col-6">
                                    <label for="icon">{{ Translate::get('module_translates::admin/main.icon') }}
                                        :</label>
                                    <select class="select2-icon" id="icon" name="icon">
                                        @foreach ($flags as $flag)
                                            <option @if ($flag === ($item->icon ?? old('icon'))) selected="selected"
                                                    @endif data-icon="flag-icon {{$flag}}"
                                                    value="{{$flag}}">{{$flag}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group text-center pt-3 col-12">
                                    <button type="submit" class="btn btn-primary">
                                        {{ Translate::get('module_translates::admin/main.action_save') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop