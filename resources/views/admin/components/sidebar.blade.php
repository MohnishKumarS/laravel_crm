  <!-- Sidebar -->
  <div class="sidebar" data-background-color="dark">
      <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
              <a href="{{ url('/') }}" class="logo" target="_blank">
                  <img src="{{ asset('uploads/logo/logo_light.png') }}" alt="navbar brand" class="navbar-brand"
                      width="100" />
              </a>
              <div class="nav-toggle">
                  <button class="btn btn-toggle toggle-sidebar">
                      <i class="gg-menu-right"></i>
                  </button>
                  <button class="btn btn-toggle sidenav-toggler">
                      <i class="gg-menu-left"></i>
                  </button>
              </div>
              <button class="topbar-toggler more">
                  <i class="gg-more-vertical-alt"></i>
              </button>
          </div>
          <!-- End Logo Header -->
      </div>
      @role('admin')
          <div class="sidebar-wrapper scrollbar scrollbar-inner">
              <div class="sidebar-content">
                  <ul class="nav nav-secondary">
                      <li class="nav-item active">
                          <a href="{{ url('/') }}">
                              <i class="fas fa-home"></i>
                              <p>Dashboard</p>
                              {{-- <span class="caret"></span> --}}
                          </a>
                          {{-- <div class="collapse" id="dashboard">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="../demo1/index.html">
                                            <span class="sub-item">Dashboard 1</span>
                                        </a>
                                    </li>
                                </ul>
                            </div> --}}
                      </li>


                      <li class="nav-section">
                          <span class="sidebar-mini-icon">
                              <i class="fa fa-ellipsis-h"></i>
                          </span>
                          <h4 class="text-section">Components</h4>
                      </li>

                      {{-- <li class="nav-item">
                      <a data-bs-toggle="collapse" href="#brands_list">
                          <i class="fas fa-ghost"></i>
                          <p>Users</p>
                          <span class="caret"></span>
                      </a>
                      <div class="collapse" id="brands_list">
                          <ul class="nav nav-collapse" style="list-style: none">
                              <li>
                                  <a href="{{ route('brands.create') }}">
                                      <span class="ms-5">Add Users</span>
                                  </a>
                              </li>
                              <li>
                                  <a href="{{ route('brands.index') }}">
                                      <span class="ms-5">View Users</span>
                                  </a>
                              </li>
                          </ul>
                      </div>
                  </li> --}}
                      <li class="nav-item {{ request()->routeIs('forms.*') ? 'active' : '' }}">
                          <a data-bs-toggle="collapse" href="#forms_list">
                              <i class="fas fa-book-open"></i>
                              <p>Forms</p>
                              <span class="caret"></span>
                          </a>
                          <div class="collapse  {{ request()->routeIs('forms.*') ? 'show' : '' }}" id="forms_list">
                              <ul class="nav nav-collapse" style="list-style: none">
                                  <li>
                                      <a href="{{ route('forms.create') }}">
                                          <span class="ms-5">Add Form</span>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ route('forms.index') }}">
                                          <span class="ms-5">View Forms</span>
                                      </a>
                                  </li>
                              </ul>
                          </div>
                      </li>
                      <li class="nav-item {{ request()->routeIs('posts.*') ? 'active' : '' }}">
                          <a data-bs-toggle="collapse" href="#posts_list">
                              <i class="fas fa-newspaper"></i>
                              <p>Posts</p>
                              <span class="caret"></span>
                          </a>
                          <div class="collapse  {{ request()->routeIs('posts.*') ? 'show' : '' }}" id="posts_list">
                              <ul class="nav nav-collapse" style="list-style: none">
                                  <li class="nav-item">
                                      <a href="{{ route('posts.create') }}">
                                          <span class="ms-5">Add Post</span>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ route('posts.index') }}">
                                          <span class="ms-5">View Posts</span>
                                      </a>
                                  </li>
                              </ul>
                          </div>
                      </li>
                      <li class="nav-item {{ request()->routeIs('admin.campaigns.*') ? 'active' : '' }}">
                          <a data-bs-toggle="collapse" href="#campaigns_list">
                              <i class="fas fa-bullhorn"></i>
                              <p>Campaigns</p>
                              <span class="caret"></span>
                          </a>
                          <div class="collapse  {{ request()->routeIs('admin.campaigns.*') ? 'show' : '' }}"
                              id="campaigns_list">
                              <ul class="nav nav-collapse" style="list-style: none">
                                  <li>
                                      <a href="{{ route('admin.campaigns.create') }}">
                                          <span class="ms-5">Add Campaign</span>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ route('admin.campaigns.index') }}">
                                          <span class="ms-5">View Campaigns</span>
                                      </a>
                                  </li>
                              </ul>
                          </div>
                      </li>
                      <li class="nav-item {{ request()->routeIs('admin.home-hero.*') ? 'active' : '' }}">
                          <a data-bs-toggle="collapse" href="#home_hero_list">
                              <i class="fas fa-image"></i>
                              <p>Home Hero</p>
                              <span class="caret"></span>
                          </a>
                          <div class="collapse  {{ request()->routeIs('admin.home-hero.*') ? 'show' : '' }}"
                              id="home_hero_list">
                              <ul class="nav nav-collapse" style="list-style: none">
                                  <li>
                                      <a href="{{ route('admin.home-hero.create') }}">
                                          <span class="ms-5">Add Home Hero</span>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ route('admin.home-hero.index') }}">
                                          <span class="ms-5">View Home Hero</span>
                                      </a>
                                  </li>
                              </ul>
                          </div>
                      </li>
                      <li class="nav-item {{ request()->routeIs('affiliates.*') ? 'active' : '' }}">
    <a data-bs-toggle="collapse" href="#affiliate_program_list">
        <i class="fas fa-handshake"></i>
        <p>Affiliate Program</p>
        <span class="caret"></span>
    </a>
    <div class="collapse {{ request()->routeIs('affiliates.*') ? 'show' : '' }}"
        id="affiliate_program_list">
        <ul class="nav nav-collapse" style="list-style: none">
            <li>
                <a href="{{ route('affiliates.index') }}">
                    <span class="ms-5">All Affiliates</span>
                </a>
            </li>
            <li>
                <a href="{{ route('affiliates.commissions') }}">
                    <span class="ms-5">Commissions</span>
                </a>
            </li>
            <li>
                <a href="{{ route('affiliates.payouts') }}">
                    <span class="ms-5">Payouts</span>
                </a>
            </li>
            <li>
                <a href="{{ route('affiliates.settings.edit') }}">
                    <span class="ms-5">Settings</span>
                </a>
            </li>
        </ul>
    </div>
</li>
                  
                  <li class="nav-item {{ request()->routeIs('analytics.*') ? 'active' : '' }}">
                          <a data-bs-toggle="collapse" href="#analytic_list">
                              <i class="fas fa-chart-bar"></i>
                              <p>Analytics</p>
                              <span class="caret"></span>
                          </a>
                          <div class="collapse  {{ request()->routeIs('analytics.*') ? 'show' : '' }}" id="analytic_list">
                              <ul class="nav nav-collapse" style="list-style: none">
                                  <li>
                                      <a href="{{ route('analytics.visitors') }}">
                                          <span class="ms-5">Yuukke Visitors</span>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ route('analytics.shop') }}">
                                          <span class="ms-5">Marketplace Visitors</span>
                                      </a>
                                  </li>



                              </ul>

                          </div>
                      </li>
                      <li class="nav-item {{ request()->is('settings*') ? 'active' : '' }}">
                          <a data-bs-toggle="collapse" href="#settings_list">
                              <i class="fas fa-gear"></i>
                              <p>Settings</p>
                              <span class="caret"></span>
                          </a>
                          <div class="collapse  {{ request()->is('settings*') ? 'show' : '' }}" id="settings_list">
                              <ul class="nav nav-collapse" style="list-style: none">
                                  <li>
                                      <a href="{{ route('settings') }}">
                                          <span class="ms-5">View Setting</span>
                                      </a>
                                  </li>
                              </ul>
                          </div>
                      </li>

                      <li class="nav-section">
                          <span class="sidebar-mini-icon">
                              <i class="fa fa-ellipsis-h"></i>
                          </span>
                          <h4 class="text-section">Shop</h4>
                      </li>



                      {{-- MARKETPLACE --}}
                      <li class="nav-item submenu {{ request()->routeIs('shop.*') ? 'active' : '' }}">
                          <a data-bs-toggle="collapse" href="#submenu" class="collapsed" aria-expanded="false">
                              <i class="fas fa-store"></i>
                              <p>Marketplace</p>
                              <span class="caret"></span>
                          </a>
                          <div class="collapse {{ request()->routeIs('shop.*') ? 'show' : '' }}" id="submenu"
                              style="">
                              <ul class="nav nav-collapse">
                                  <li>
                                      <a href="{{ route('shop.home') }}">
                                          <span class="sub-item">Dashboard</span>
                                      </a>
                                  </li>
                                  {{-- <li class="submenu">
                                  <a data-bs-toggle="collapse" href="#subnav1" class="collapsed"
                                      aria-expanded="false">
                                      <span class="sub-item">Level 1</span>
                                      <span class="caret"></span>
                                  </a>
                                  <div class="collapse" id="subnav1" style="">
                                      <ul class="nav nav-collapse subnav">
                                          <li>
                                              <a href="#">
                                                  <span class="sub-item">Level 2</span>
                                              </a>
                                          </li>
                                          <li>
                                              <a href="#">
                                                  <span class="sub-item">Level 2</span>
                                              </a>
                                          </li>
                                      </ul>
                                  </div>
                              </li> --}}


                              </ul>
                          </div>
                      </li>




                      {{-- @role(['admin'])
                      <h1>Welcome Admin</h1>
                       @else
                       <h1>Welocme</h1>
                  @endrole --}}






                  </ul>
              </div>
          </div>
      @endrole()

      @role('marketer')
          <div class="sidebar-wrapper scrollbar scrollbar-inner">
              <div class="sidebar-content">
                  <ul class="nav nav-secondary">
                      <li class="nav-item active">
                          <a href="{{ route('marketer.dashboard') }}">
                              <i class="fas fa-home"></i>
                              <p>Dashboard</p>
                              {{-- <span class="caret"></span> --}}
                          </a>
                          {{-- <div class="collapse" id="dashboard">
                                <ul class="nav nav-collapse">
                                    <li>
                                        <a href="../demo1/index.html">
                                            <span class="sub-item">Dashboard 1</span>
                                        </a>
                                    </li>
                                </ul>
                            </div> --}}
                      </li>


                      <li class="nav-section">
                          <span class="sidebar-mini-icon">
                              <i class="fa fa-ellipsis-h"></i>
                          </span>
                          <h4 class="text-section">Components</h4>
                      </li>


                  </ul>
              </div>
          </div>
      @endrole
  </div>
  <!-- End Sidebar -->
