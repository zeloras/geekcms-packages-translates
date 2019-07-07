@extends('admin.layouts.main')

@section('title', \Translate::get('translates::admin/sidenav.name'))

@section('content')

<section class="box-typical container pb-3">
    <header class="box-typical-header">
        <div class="tbl-row">
            <div class="tbl-cell tbl-cell-title">
                <h3>{{ \Translate::get('module_translates::admin/main.translates_list') }}</h3>
            </div>
            <div class="tbl-cell tbl-cell-action-bordered">
                <a href="{{ route('admin.translates.create') }}"
                   data-toggle="tooltip" data-placement="left"
                   data-original-title="{{ \Translate::get('module_translates::admin/main.create_translates') }}" class="action-btn">
                    <i class="fa fa-plus"></i>
                </a>
            </div>
            <div class="tbl-cell tbl-cell-action-bordered">
                <button type="button" data-token="{!! csrf_token() !!}"
                        data-toggle="tooltip" data-placement="left"
                        data-original-title="{{ \Translate::get('module_translates::admin/main.delete_selected_languages') }}"
                        data-text="Are you sure?" data-inputs=".delete-item-check:checked"
                        data-action="{{ route('admin.translates.delete.all') }}"
                        class="action-btn delete-all">
                    <i class="font-icon font-icon-trash"></i>
                </button>
            </div>
        </div>
    </header>
    <div class="box-typical-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-custom">
                <thead>
                <tr>
                    <th class="table-check"></th>
                    <th class="table-title">{{ \Translate::get('module_translates::admin/main.name') }}</th>
                    <th>{{ \Translate::get('module_translates::admin/main.code') }}</th>
                    <th>{{ \Translate::get('module_translates::admin/main.icon') }}</th>
                    <th>{{ \Translate::get('module_translates::admin/main.created_at') }}</th>
                    <th class="table-icon-cell table-actions"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($elements as $element)
                    <tr>
                        <td class="table-check">
                            <div class="checkbox checkbox-only">
                                <input type="checkbox" class="delete-item-check" id="table-check-{{ $element->id }}" value="{{ $element->id }}">
                                <label for="table-check-{{ $element->id }}"></label>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('admin.translates.list', ['lang' => $element->id]) }}">
                                {{ $element->name }}
                            </a>
                        </td>
                        <td class="color-blue-grey-lighter">
                            {{ $element->key }}
                        </td>
                        <td class="color-blue-grey-lighter">
                            <i class="flag-icon {{ $element->icon }}"></i>
                        </td>
                        <td class="table-date">{{ $element->created_at }} <i class="font-icon font-icon-clock"></i></td>
                        <td class="table-icon-cell">
                            <a href="{{ route('admin.translates.edit', ['lang' => $element->id]) }}"
                               data-toggle="tooltip" data-placement="left"
                               data-original-title="{{ \Translate::get('module_translates::admin/main.action_edit') }}"
                               class="btn-link btn btn-success-outline btn-sm">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a href="{{ route('admin.translates.delete', ['lang' => $element->id]) }}"
                               data-toggle="tooltip" data-placement="left"
                               data-original-title="{{ \Translate::get('module_translates::admin/main.action_delete') }}"
                               class="btn-link btn btn-success-outline btn-sm"
                               data-delete="{{ \Translate::get('module_translates::admin/main.action_delete_confirm') }}">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

@endsection