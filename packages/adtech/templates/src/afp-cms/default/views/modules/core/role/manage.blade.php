@extends('layouts.default')
@section('title'){{ $title = trans('adtech-core::titles.role.manage') }}@stop

@section('content')
    <div layout-no-padding="" ng-controller="roleCtrl">
        <md-card>
            <div class="box_breadcrumb">
                <ul class="breadcrumb">
                    <li><a href="{{ route('backend.homepage') }}">{{ trans('adtech-core::labels.home') }}</a></li>
                    <li><h2 class="md-title">{{ $title }}</h2></li>
                </ul>
                <div class="action_breadcrumb" layout="row">
                    <md-button class="md-raised md-primary" ng-click="addItem($event)">
                        <md-icon>add_box</md-icon>{{ trans('adtech-core::buttons.create') }}
                    </md-button>
                </div>
            </div>
        </md-card>
        <md-card style="padding-top: 10px">
            <md-table-container style="margin: 20px 0px">
                <table md-table multiple>
                    <thead md-head>
                    <tr md-row>
                        <th width="20" md-column><span>{{ $labelId = trans('adtech-core::common.id') }}</span></th>
                        <th md-column md-text><span>{{ $labelName = trans('adtech-core::common.role.name') }}</span></th>
                        <th width="90" align="center" md-column
                            md-numeric>{{ $labelAction = trans('adtech-core::common.action') }}
                        </th>
                    </tr>
                    </thead>
                    <tbody md-body>
                    <tr md-row md-select="role.id" md-select-id="role.id" md-auto-select ng-repeat="role in roleList">
                        <td md-cell>@{{role.id}}</td>
                        <td md-cell>
                            <a ng-if="role.permission_locked == 0" ng-click="showEdit($event, role.id)">
                                <strong>@{{role.name}}</strong>
                            </a>
                            <strong ng-if="role.permission_locked != 0">@{{role.name}}</strong>
                        </td>
                        <td md-cell>
                            <md-menu md-position-mode="target-right target" ng-if="role.permission_locked == 0">
                                <md-button aria-label="Open demo menu" class="md-icon-button"
                                           ng-click="$mdMenu.open($event)">
                                    <md-icon class="md-default-theme" class="material-icons">settings</md-icon>
                                </md-button>
                                <md-menu-content width="4">
                                    <md-menu-item>
                                        <md-button ng-click="showEdit($event, role.id)">
                                            <div layout="row" flex>
                                                <p flex>{{ trans('adtech-core::common.actions.edit') }}</p>
                                                <md-icon class="md-default-theme" class="material-icons"
                                                         style="margin: auto 3px auto 0;">edit
                                                </md-icon>
                                            </div>
                                        </md-button>
                                    </md-menu-item>
                                    <md-menu-item>
                                        <md-button ng-click="showDel($event, role.id)">
                                            <div layout="row" flex>
                                                <p flex>{{ trans('adtech-core::common.actions.delete') }}</p>
                                                <md-icon class="md-default-theme" class="material-icons"
                                                         style="margin: auto 3px auto 0;">delete
                                                </md-icon>
                                            </div>
                                        </md-button>
                                    </md-menu-item>
                                    <md-menu-item>
                                        <md-button ng-click="permissionDetails(role.url_permission_details)"
                                                   class="adtech-click-loading">
                                            <div layout="row" flex>
                                                <p flex>{{ trans('adtech-core::common.actions.permission') }}</p>
                                                <md-icon class="md-default-theme" class="material-icons"
                                                         style="margin: auto 3px auto 0;">transfer_within_a_station
                                                </md-icon>
                                            </div>
                                        </md-button>
                                    </md-menu-item>
                                </md-menu-content>
                            </md-menu>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </md-table-container>

            <md-table-pagination md-label="{page: '{{ trans('adtech-core::labels.page') }}', rowsPerPage: '{{ trans('adtech-core::labels.rowsPerPage') }}', of: '{{ trans('adtech-core::labels.of') }}'}"
                                 md-limit="query.limit" md-limit-options="[10, 30, 50, 100]" md-page="query.page"
                                 md-total="@{{ query.total }}" md-on-paginate="getRoles"
                                 md-page-select></md-table-pagination>
        </md-card>
    </div>
@stop

@push('scripts-view')
<script type="text/ng-template" id="frm-add-role">
    <md-dialog flex="60">
        <md-toolbar>
            <div class="md-toolbar-tools">
                <h2>{{ trans('adtech-core::titles.role.add') }}</h2>
                <span flex></span>
                <md-button class="md-icon-button" ng-click="closeDialog()">
                    <i class="material-icons">close</i>
                </md-button>
            </div>
        </md-toolbar>
        <md-dialog-content style="padding: 2em;">
            <form name="myForm">
                <md-input-container class="md-icon-float md-block">
                    <label>{{ trans('adtech-core::labels.name') }}</label>
                    <input ng-model="role.name" type="text" required>
                </md-input-container>
            </form>
        </md-dialog-content>
        <md-dialog-actions>
            <md-button ng-click="addRole()" ng-disabled="myForm.$invalid" class="md-raised md-primary">
                {{ trans('adtech-core::buttons.save') }}
            </md-button>
        </md-dialog-actions>
    </md-dialog>
</script>
<script type="text/ng-template" id="frm-edit-role">
    <md-dialog flex="60">
        <md-toolbar>
            <div class="md-toolbar-tools">
                <h2>{{ trans('adtech-core::titles.role.edit') }}</h2>
                <span flex></span>
                <md-button class="md-icon-button" ng-click="closeDialog()">
                    <i class="material-icons">close</i>
                </md-button>
            </div>
        </md-toolbar>
        <md-dialog-content style="padding: 2em;">
            <form name="myForm">
                <md-input-container class="md-icon-float md-block">
                    <label>{{ trans('adtech-core::labels.name') }}</label>
                    <input ng-model="role.name" type="text" required>
                    <input ng-model="role.role_id" type="hidden">
                </md-input-container>
            </form>
        </md-dialog-content>
        <md-dialog-actions>
            <md-button ng-click="showDel($event, role.role_id)" class="md-raised md-warn">
                {{trans('adtech-core::buttons.delete')}}
            </md-button>
            <md-button ng-click="updateRole()" ng-disabled="myForm.$invalid" class="md-raised md-primary">
                {{ trans('adtech-core::buttons.save') }}
            </md-button>
        </md-dialog-actions>
    </md-dialog>
</script>
<script type="text/javascript">
    var coreRoles = $.parseJSON('{!! $jsonRoleString !!}');
    angular.module("AdtechApp")
        .run(function ($rootScope) {
            $rootScope.getRoles = function () {
                var query = $rootScope.query;
                var url = '{{ route('adtech.core.role.manage') }}',
                    params = ['page=' + query.page, 'limit=' + query.limit];
                AdtechApp.loading.show();
                window.location.href = (url + '?' + params.join('&'));
            };
            $rootScope.roleList = coreRoles;
            $rootScope.selected = [];
            $rootScope.query = {
                filter: '',
                total: {{ $total }},
                limit: {{ $limit }},
                order: 'role.name',
                page: {{ $pageIndex }}
            };
            $rootScope.permissionDetails = function (url) {
                window.location.href = (url);
            };
        });
    angular.module("AdtechApp")
        .constant("CSRF_TOKEN", '{{ csrf_token() }}')
        .run(['$http', 'CSRF_TOKEN', function ($http, CSRF_TOKEN) {
            $http.defaults.headers.common['X-Csrf-Token'] = CSRF_TOKEN;
        }]);
</script>
<script src="{{ url('/vendor/' . $group_name . '/' . $skin . '/js/controllers/role.js?t=').time() }}"></script>
@endPush
