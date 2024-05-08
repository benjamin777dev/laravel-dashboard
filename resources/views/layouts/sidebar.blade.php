<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">@lang('Main Menu')</li>
                <li>
                    <a href="{{ route('dashboard.index') }}" class="waves-effect">
                        {{-- <i class="bx bx-home-circle"></i> --}}
                        <img src="{{ URL::asset('/images/dashboard.svg') }}" class="sidebarIcons" alt="D">

                        <span key="t-dashboard">@lang('Dashboard')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('pipeline.index') }}" class="waves-effect">
                        {{-- <i class="bx bx-home-circle"></i> --}}
                        <img src="{{ URL::asset('/images/polyline.svg') }}" class="sidebarIcons" alt="P">

                        <span key="t-pipeline">@lang('Pipeline')</span>
                    </a>
                </li>
                <li>
                    <a  class="has-arrow waves-effect">
                        {{-- <i class="bx bx-user-circle"></i> --}}
                        <img src="{{ URL::asset('/images/contacts.svg') }}" class="sidebarIcons" alt="C">

                        <span key="t-database">@lang('Database')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        {{-- <li><a href="{{ route('contacts.index') }}" key="t-Contacts">@lang('Contacts Database')</a></li> --}}
                         <li><a href="{{ route('contacts.index') }}" key="t-database">@lang('Contacts Database')</a></li> 
                        {{-- <li><a href="{{ route('contacts.index') }}" key="t-Contacts">@lang('testing route')</a></li> --}}
                         <li><a href="{{ route('groups.index') }}" key="t-database">@lang('Groups')</a></li>

                    </ul>
                </li>
                {{-- <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-user-circle"></i>
                        <img src="{{ URL::asset('/images/campaign.svg') }}" class="sidebarIcons" alt="Ca">

                        <span key="t-marketing">@lang('Marketing')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="#" key="t-marketing">@lang('Marketing')</a></li>
                        <li><a href="#" key="t-marketing">@lang('Marketing')</a></li>
                        <li><a href="#" key="t-marketing">@lang('Marketing')</a></li>
                        <li><a href="#" key="t-marketing">@lang('Marketing')</a></li>
                        <li><a href="#" key="t-marketing">@lang('Marketing')</a></li>
                    </ul>
                </li> --}}

                <li class="menu-title" key="t-backend">@lang('Activities')</li>
                <li>
                    <a href="{{ route('task.index') }}" class="waves-effect">
                        <img src="{{ URL::asset('/images/sidebarSplit.svg') }}" class="sidebarIcons" alt="Sp">

                        {{-- <i class="bx bx-list-ul"></i> --}}
                        <span key="t-tasks">@lang('Tasks')</span>
                    </a>
                </li>
                {{-- <li>
                    <a href="dashboard" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboard">@lang('Dashboard')</span>
                    </a>
                </li>
                <li>
                    <a href="pipeline" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-pipeline">@lang('Pipeline')</span>
                    </a>
                </li>
                <li>
                    <a href="contacts" class="waves-effect">
                        <i class="bx bx-user-circle"></i>
                        <span key="t-database">@lang('Database')</span>
                    </a>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-user-circle"></i>
                        <span key="t-marketing">@lang('Marketing')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="marketing" key="t-marketing">@lang('Marketing')</a></li>
                        <li><a href="marketing" key="t-marketing">@lang('Marketing')</a></li>
                        <li><a href="marketing" key="t-marketing">@lang('Marketing')</a></li>
                        <li><a href="marketing" key="t-marketing">@lang('Marketing')</a></li>
                        <li><a href="marketing" key="t-marketing">@lang('Marketing')</a></li>
                    </ul>
                </li>

                <li class="menu-title" key="t-backend">@lang('Activities')</li>
                <li>
                    <a href="#" class="waves-effect">
                        <i class="bx bx-list-ul"></i>
                        <span key="t-tasks">@lang('Tasks')</span>
                    </a>
                </li> --}}

                <!-- <li>
                    <a href="javascript: void(0);" class="waves-effect">
                    <span class="badge rounded-pill bg-danger float-end" key="t-hot">@lang('hot')</span>
                        <i class="bx bx-layout"></i>
                        <span key="t-layouts">@lang('Layouts')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li>
                            <a href="javascript: void(0);" class="has-arrow"
                                key="t-vertical">@lang('Vertical')</a>
                            <ul class="sub-menu" aria-expanded="true">
                                <li><a href="layouts-light-sidebar"
                                        key="t-light-sidebar">@lang('Light_Sidebar')</a></li>
                                <li><a href="layouts-compact-sidebar"
                                        key="t-compact-sidebar">@lang('Compact_Sidebar')</a></li>
                                <li><a href="layouts-icon-sidebar"
                                        key="t-icon-sidebar">@lang('Icon_Sidebar')</a></li>
                                <li><a href="layouts-boxed" key="t-boxed-width">@lang('Boxed_Width')</a>
                                </li>
                                <li><a href="layouts-preloader" key="t-preloader">@lang('Preloader')</a>
                                </li>
                                <li><a href="layouts-colored-sidebar"
                                        key="t-colored-sidebar">@lang('Colored_Sidebar')</a></li>
                                <li><a href="layouts-scrollable" key="t-scrollable">@lang('Scrollable')</a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="javascript: void(0);" class="has-arrow"
                                key="t-horizontal">@lang('Horizontal')</a>
                            <ul class="sub-menu" aria-expanded="true">
                                <li><a href="layouts-horizontal" key="t-horizontal">@lang('Horizontal')</a>
                                </li>
                                <li><a href="layouts-hori-topbar-light"
                                        key="t-topbar-light">@lang('Topbar_Light')</a></li>
                                <li><a href="layouts-hori-boxed-width"
                                        key="t-boxed-width">@lang('Boxed_Width')</a></li>
                                <li><a href="layouts-hori-preloader"
                                        key="t-preloader">@lang('Preloader')</a></li>
                                <li><a href="layouts-hori-colored-header"
                                        key="t-colored-topbar">@lang('Colored_Header')</a></li>
                                <li><a href="layouts-hori-scrollable"
                                        key="t-scrollable">@lang('Scrollable')</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li class="menu-title" key="t-backend">@lang('backend')</li>
                <li>
                    <a href="yajra-datatable" class="waves-effect">
                        <i class="bx bx-list-ul"></i>
                        <span key="t-yajra-datatable">@lang('yajra-datatable')</span>
                    </a>
                </li>

                <li class="menu-title" key="t-apps">@lang('Apps')</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-calendar"></i>
                        <span key="t-dashboards">@lang('Calendars')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="calendar" key="t-tui-calendar">@lang('TUI_Calendar')</a></li>
                        <li><a href="calendar-full" key="t-full-calendar">@lang('Full_Calendar')</a></li>
                    </ul>
                </li>

                <li>
                    <a href="chat" class="waves-effect">
                        <i class="bx bx-chat"></i>
                        <span key="t-chat">@lang('Chat')</span>
                    </a>
                </li>

                <li>
                    <a href="apps-filemanager" class="waves-effect">
                        <i class="bx bx-file"></i>
                        <span key="t-file-manager">@lang('File_Manager')</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-store"></i>
                        <span key="t-ecommerce">@lang('Ecommerce')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="ecommerce-products" key="t-products">@lang('Products')</a></li>
                        <li><a href="ecommerce-product-detail"
                                key="t-product-detail">@lang('Product_Detail')</a></li>
                        <li><a href="ecommerce-orders" key="t-orders">@lang('Orders')</a></li>
                        <li><a href="ecommerce-customers" key="t-customers">@lang('Customers')</a></li>
                        <li><a href="ecommerce-cart" key="t-cart">@lang('Cart')</a></li>
                        <li><a href="ecommerce-checkout" key="t-checkout">@lang('Checkout')</a></li>
                        <li><a href="ecommerce-shops" key="t-shops">@lang('Shops')</a></li>
                        <li><a href="ecommerce-add-product" key="t-add-product">@lang('Add_Product')</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-bitcoin"></i>
                        <span key="t-crypto">@lang('Crypto')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="crypto-wallet" key="t-wallet">@lang('Wallet')</a></li>
                        <li><a href="crypto-buy-sell" key="t-buy">@lang('Buy_Sell')</a></li>
                        <li><a href="crypto-exchange" key="t-exchange">@lang('Exchange')</a></li>
                        <li><a href="crypto-lending" key="t-lending">@lang('Lending')</a></li>
                        <li><a href="crypto-orders" key="t-orders">@lang('Orders')</a></li>
                        <li><a href="crypto-kyc-application" key="t-kyc">@lang('KYC_Application')</a></li>
                        <li><a href="crypto-ico-landing" key="t-ico">@lang('ICO_Landing')</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-envelope"></i>
                        <span key="t-email">@lang('Email')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="email-inbox" key="t-inbox">@lang('Inbox')</a></li>
                        <li><a href="email-read" key="t-read-email">@lang('Read_Email')</a></li>
                        <li>
                            <a href="javascript: void(0);">
                                <span key="t-email-templates">@lang('Templates')</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="true">
                                <li><a href="email-template-basic"
                                        key="t-basic-action">@lang('Basic_Action')</a></li>
                                <li><a href="email-template-alert"
                                        key="t-alert-email">@lang('Alert_Email')</a></li>
                                <li><a href="email-template-billing"
                                        key="t-bill-email">@lang('Billing_Email')</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-receipt"></i>
                        <span key="t-invoices">@lang('Invoices')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="invoices-list" key="t-invoice-list">@lang('Invoice_List')</a></li>
                        <li><a href="invoices-detail" key="t-invoice-detail">@lang('Invoice_Detail')</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-briefcase-alt-2"></i>
                        <span key="t-projects">@lang('Projects')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="projects-grid" key="t-p-grid">@lang('Projects_Grid')</a></li>
                        <li><a href="projects-list" key="t-p-list">@lang('Projects_List')</a></li>
                        <li><a href="projects-overview" key="t-p-overview">@lang('Project_Overview')</a>
                        </li>
                        <li><a href="projects-create" key="t-create-new">@lang('Create_New')</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-task"></i>
                        <span key="t-tasks">@lang('Tasks')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="tasks-list" key="t-task-list">@lang('Task_List')</a></li>
                        <li><a href="tasks-kanban" key="t-kanban-board">@lang('Kanban_Board')</a></li>
                        <li><a href="tasks-create" key="t-create-task">@lang('Create_Task')</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bxs-user-detail"></i>
                        <span key="t-contacts">@lang('Contacts')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="contacts-grid" key="t-user-grid">@lang('User_Grid')</a></li>
                        <li><a href="contacts-list" key="t-user-list">@lang('User_List')</a></li>
                        <li><a href="contacts-profile" key="t-profile">@lang('Profile')</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-detail"></i>
                        <span key="t-blog">@lang('Blog')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="blog-list" key="t-blog-list">@lang('Blog_List')</a></li>
                        <li><a href="blog-grid" key="t-blog-grid">@lang('Blog_Grid')</a></li>
                        <li><a href="blog-details" key="t-blog-details">@lang('Blog_Details')</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="waves-effect has-arrow">
                             <i class="bx bx-briefcase-alt"></i>
                        <span key="t-jobs">@lang('Jobs')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="job-list" key="t-job-list">@lang('Job_List')</a></li>
                        <li><a href="job-grid" key="t-job-grid">@lang('Job_Grid')</a></li>
                        <li><a href="job-apply" key="t-apply-job">@lang('Apply_Job')</a></li>
                        <li><a href="job-details" key="t-job-details">@lang('Job_Details')</a></li>
                        <li><a href="job-categories" key="t-Jobs-categories">@lang('Jobs_Categories')</a></li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow" key="t-candidate">@lang('Candidate')</a>
                            <ul class="sub-menu" aria-expanded="true">
                                <li><a href="candidate-list" key="t-list">@lang('List')</a></li>
                                <li><a href="candidate-overview" key="t-overview">@lang('Overview')</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li class="menu-title" key="t-pages">@lang('Pages')</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-user-circle"></i>
                        <span key="t-authentication">@lang('Authentication')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="auth-login" key="t-login">@lang('Login')</a></li>
                        <li><a href="auth-login-2" key="t-login-2">@lang('Login') 2</a></li>
                        <li><a href="auth-register" key="t-register">@lang('Register')</a></li>
                        <li><a href="auth-register-2" key="t-register-2">@lang('Register') 2</a></li>
                        <li><a href="auth-recoverpw" key="t-recover-password">@lang('Recover_Password')</a>
                        </li>
                        <li><a href="auth-recoverpw-2" key="t-recover-password-2">@lang('Recover_Password')
                                2</a></li>
                        <li><a href="auth-lock-screen" key="t-lock-screen">@lang('Lock_Screen')</a></li>
                        <li><a href="auth-lock-screen-2" key="t-lock-screen-2">@lang('Lock_Screen') 2</a>
                        </li>
                        <li><a href="auth-confirm-mail" key="t-confirm-mail">@lang('Confirm_Mail')</a></li>
                        <li><a href="auth-confirm-mail-2" key="t-confirm-mail-2">@lang('Confirm_Mail') 2</a>
                        </li>
                        <li><a href="auth-email-verification"
                                key="t-email-verification">@lang('Email_verification')</a></li>
                        <li><a href="auth-email-verification-2"
                                key="t-email-verification-2">@lang('Email_verification') 2</a></li>
                        <li><a href="auth-two-step-verification"
                                key="t-two-step-verification">@lang('Two_step_verification')</a></li>
                        <li><a href="auth-two-step-verification-2"
                                key="t-two-step-verification-2">@lang('Two_step_verification') 2</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-file"></i>
                        <span key="t-utility">@lang('Utility')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="pages-starter" key="t-starter-page">@lang('Starter_Page')</a></li>
                        <li><a href="pages-maintenance" key="t-maintenance">@lang('Maintenance')</a></li>
                        <li><a href="pages-comingsoon" key="t-coming-soon">@lang('Coming_Soon')</a></li>
                        <li><a href="pages-timeline" key="t-timeline">@lang('Timeline')</a></li>
                        <li><a href="pages-faqs" key="t-faqs">@lang('FAQs')</a></li>
                        <li><a href="pages-pricing" key="t-pricing">@lang('Pricing')</a></li>
                        <li><a href="pages-404" key="t-error-404">@lang('Error_404')</a></li>
                        <li><a href="pages-500" key="t-error-500">@lang('Error_500')</a></li>
                    </ul>
                </li>

                <li class="menu-title" key="t-components">@lang('Components')</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-tone"></i>
                        <span key="t-ui-elements">@lang('UI_Elements')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="ui-alerts" key="t-alerts">@lang('Alerts')</a></li>
                        <li><a href="ui-buttons" key="t-buttons">@lang('Buttons')</a></li>
                        <li><a href="ui-cards" key="t-cards">@lang('Cards')</a></li>
                        <li><a href="ui-carousel" key="t-carousel">@lang('Carousel')</a></li>
                        <li><a href="ui-dropdowns" key="t-dropdowns">@lang('Dropdowns')</a></li>
                        <li><a href="ui-grid" key="t-grid">@lang('Grid')</a></li>
                        <li><a href="ui-images" key="t-images">@lang('Images')</a></li>
                        <li><a href="ui-lightbox" key="t-lightbox">@lang('Lightbox')</a></li>
                        <li><a href="ui-modals" key="t-modals">@lang('Modals')</a></li>
                        <li><a href="ui-offcanvas" key="t-offcanvas">@lang('Offcanvas')</a></li>
                        <li><a href="ui-rangeslider" key="t-range-slider">@lang('Range_Slider')</a></li>
                        <li><a href="ui-session-timeout" key="t-session-timeout">@lang('Session_Timeout')</a></li>
                        <li><a href="ui-progressbars" key="t-progress-bars">@lang('Progress_Bars')</a></li>
                        <li><a href="ui-placeholders" key="t-placeholders">@lang('Placeholders')</a></li>
                        <li><a href="ui-sweet-alert" key="t-sweet-alert">@lang('Sweet_Alert')</a></li>
                        <li><a href="ui-tabs-accordions" key="t-tabs-accordions">@lang('Tabs_&_Accordions')</a></li>
                        <li><a href="ui-typography" key="t-typography">@lang('Typography')</a></li>
                        <li><a href="ui-toasts" key="t-toasts">@lang('Toasts')</a></li>
                        <li><a href="ui-video" key="t-video">@lang('Video')</a></li>
                        <li><a href="ui-general" key="t-general">@lang('General')</a></li>
                        <li><a href="ui-colors" key="t-colors">@lang('Colors')</a></li>
                        <li><a href="ui-rating" key="t-rating">@lang('Rating')</a></li>
                        <li><a href="ui-notifications" key="t-notifications">@lang('Notifications')</a></li>
                        <li><a href="ui-utilities">@lang('Utilities')</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="waves-effect">
                        <i class="bx bxs-eraser"></i>
                        <span class="badge rounded-pill bg-danger float-end">10</span>
                        <span key="t-forms">@lang('Forms')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="form-elements" key="t-form-elements">@lang('Form_Elements')</a></li>
                        <li><a href="form-layouts" key="t-form-layouts">@lang('Form_Layouts')</a></li>
                        <li><a href="form-validation" key="t-form-validation">@lang('Form_Validation')</a>
                        </li>
                        <li><a href="form-advanced" key="t-form-advanced">@lang('Form_Advanced')</a></li>
                        <li><a href="form-editors" key="t-form-editors">@lang('Form_Editors')</a></li>
                        <li><a href="form-uploads" key="t-form-upload">@lang('Form_File_Upload')</a></li>
                        <li><a href="form-xeditable" key="t-form-xeditable">@lang('Form_Xeditable')</a></li>
                        <li><a href="form-repeater" key="t-form-repeater">@lang('Form_Repeater')</a></li>
                        <li><a href="form-wizard" key="t-form-wizard">@lang('Form_Wizard')</a></li>
                        <li><a href="form-mask" key="t-form-mask">@lang('Form_Mask')</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-list-ul"></i>
                        <span key="t-tables">@lang('Tables')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="tables-basic" key="t-basic-tables">@lang('Basic_Tables')</a></li>
                        <li><a href="tables-datatable" key="t-data-tables">@lang('Data_Tables')</a></li>
                        <li><a href="tables-responsive"
                                key="t-responsive-table">@lang('Responsive_Table')</a></li>
                        <li><a href="tables-editable" key="t-editable-table">@lang('Editable_Table')</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bxs-bar-chart-alt-2"></i>
                        <span key="t-charts">@lang('Charts')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="charts-apex" key="t-apex-charts">@lang('Apex_Charts')</a></li>
                        <li><a href="charts-echart" key="t-e-charts">@lang('E_Charts')</a></li>
                        <li><a href="charts-chartjs" key="t-chartjs-charts">@lang('Chartjs_Charts')</a></li>
                        <li><a href="charts-flot" key="t-flot-charts">@lang('Flot_Charts')</a></li>
                        <li><a href="charts-tui" key="t-ui-charts">@lang('Toast_UI_Charts')</a></li>
                        <li><a href="charts-knob" key="t-knob-charts">@lang('Jquery_Knob_Charts')</a></li>
                        <li><a href="charts-sparkline"
                                key="t-sparkline-charts">@lang('Sparkline_Charts')</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-aperture"></i>
                        <span key="t-icons">@lang('Icons')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="icons-boxicons" key="t-boxicons">@lang('Boxicons')</a></li>
                        <li><a href="icons-materialdesign"
                                key="t-material-design">@lang('Material_Design')</a></li>
                        <li><a href="icons-dripicons" key="t-dripicons">@lang('Dripicons')</a></li>
                        <li><a href="icons-fontawesome" key="t-font-awesome">@lang('Font_awesome')</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-map"></i>
                        <span key="t-maps">@lang('Maps')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="maps-google" key="t-g-maps">@lang('Google_Maps')</a></li>
                        <li><a href="maps-vector" key="t-v-maps">@lang('Vector_Maps')</a></li>
                        <li><a href="maps-leaflet" key="t-l-maps">@lang('Leaflet_Maps')</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-share-alt"></i>
                        <span key="t-multi-level">@lang('Multi_Level')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="javascript: void(0);" key="t-level-1-1">@lang('Level_1.1')</a></li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow"
                                key="t-level-1-2">@lang('Level_1.2')</a>
                            <ul class="sub-menu" aria-expanded="true">
                                <li><a href="javascript: void(0);" key="t-level-2-1">@lang('Level_2.1')</a>
                                </li>
                                <li><a href="javascript: void(0);" key="t-level-2-2">@lang('Level_2.2')</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li> -->

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
