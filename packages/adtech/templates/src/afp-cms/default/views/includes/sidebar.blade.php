<?php if ($USER_LOGGED) : ?>
    <md-sidenav ng-init="elevation = 3" md-whiteframe="@{{elevation}}" class="md-sidenav-left" md-component-id="left">
        <md-toolbar layout="row">
            <div class="md-toolbar-tools">
                <img height="40" src="{{ asset('vendor/' . $group_name . '/' . $skin . '/images/logo.png') }}"/>
                <span flex></span>
                <md-button class="md-icon-button" aria-label="Close Side Panel" ng-click="closeSideNavPanel()">
                    <md-tooltip>Close Side Panel</md-tooltip>
                    <md-icon class="md-default-theme" class="material-icons">cancel</md-icon>
                </md-button>
            </div>
        </md-toolbar>
        <md-content layout-no-padding="">
            <md-list>
                @if ($USER_LOGGED->canAccess('backend.homepage'))
                    <md-list-item>
                        <md-icon class="md-default-theme" class="material-icons">&#xE871;</md-icon>
                        <p><a href="{{ route("backend.homepage") }}">{{ trans('adtech-core::sidebar.dashboard') }}</a></p>
                    </md-list-item>
                @endif

            </md-list>
        </md-content>
    </md-sidenav>
<?php endif; ?>