<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">
    <div data-simplebar="init" class="h-100">
        <div class="simplebar-wrapper" style="margin: 0px;">
            <div class="simplebar-height-auto-observer-wrapper">
                <div class="simplebar-height-auto-observer"></div>
            </div>
            <div class="simplebar-mask">
                <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                    <div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: 100%; overflow: hidden;">
                        <div class="simplebar-content" style="padding: 0px;">
                            <!--- Sidemenu -->
                            <div id="sidebar-menu" class="mm-active sidebarToggleMenu">
                                <!-- Left Menu Start -->
                                <ul class="metismenu list-unstyled" id="side-menu">
                                    <li class="menu-title" key="t-menu">@lang('Main Menu')</li>
                                    <li>
                                        <a href="{{ route('dashboard.index') }}" class="waves-effect">
                                            <i class="bx bx-bar-chart-square"></i>
                                            <span key="t-dashboard">@lang('Dashboard')</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('closing.information') }}" class="waves-effect">
                                            <i class="bx bx-wallet"></i>
                                            <span key="t-closing-information">Closing Info</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('teamindividual.information') }}" class="waves-effect">
                                            <i class="bx bx-line-chart"></i>
                                            <span key="t-team-individual">Team/Individual Info</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('pipeline.index') }}" class="waves-effect">
                                            <i class="bx bx-share-alt"></i>
                                            <span key="t-pipeline">@lang('Pipeline')</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="has-arrow waves-effect" onclick="mmShow(this)">
                                            <i class="bx bx-contact"></i>
                                            <span key="t-database">@lang('Database')</span>
                                        </a>
                                        <ul class="sub-menu mm-collapse" id="showDropdown" aria-expanded="true">
                                            <li><a href="{{ route('contacts.index') }}" key="t-database">@lang('Contacts')</a></li>
                                            <li><a href="{{ route('groups.index') }}" key="t-database">@lang('Groups')</a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-title" key="t-backend">@lang('Activities')</li>
                                    <li>
                                        <a href="{{ route('task.index') }}" class="waves-effect">
                                            <i class="bx bx-task"></i>
                                            <span key="t-tasks">@lang('Tasks')</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('email.index') }}" class="waves-effect">
                                            <i class="bx bx-envelope"></i>
                                            <span key="t-tasks">@lang('Emails')</span>
                                        </a>
                                    </li>
                                    <li class="menu-title" key="t-backend">@lang('Resources')</li>
                                    <li>
                                        <a href="https://analytics.zoho.com/open-view/2487682000018362546" target="_blank" class="waves-effect">
                                            <i class="bx bx-list-ul"></i>
                                            <span key="t-tasks">@lang('CHR Rankings')</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://form.asana.com/?k=2uSi4xtUCZY7Pdcuaaycyg&d=308059472239312" target="_blank" class="waves-effect">
                                            <i class="bx bx-list-ul"></i>
                                            <span key="t-tasks">@lang('Marketing Request')</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- Sidebar -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="simplebar-placeholder" style="width: 70px; height: 1314px;"></div>
        </div>
        <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
            <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
        </div>
        <div class="simplebar-track simplebar-vertical" style="visibility: hidden;">
            <div class="simplebar-scrollbar" style="height: 0px; transform: translate3d(0px, 289px, 0px); display: none;"></div>
        </div>
    </div>
</div>

<script>
    window.mmShow = function(e) {
        let dropdownId = document.getElementById("showDropdown");

        // Check if the element already has the class
        let isActive = dropdownId.classList.contains("mm-active");

        // Toggle classes based on the current state
        if (!isActive) {
            dropdownId.classList.add("mm-active", "mm-show");
        } else {
            dropdownId.classList.remove("mm-active", "mm-show");
        }
    }
</script>
<!-- Left Sidebar End -->
