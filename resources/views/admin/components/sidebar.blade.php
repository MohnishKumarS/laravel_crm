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
                  <li class="nav-item">
                      <a data-bs-toggle="collapse" href="#forms_list">
                          <i class="fas fa-book-open"></i>
                          <p>Forms</p>
                          <span class="caret"></span>
                      </a>
                      <div class="collapse" id="forms_list">
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
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#posts_list">
                            <i class="fas fa-newspaper"></i>
                            <p>Posts</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="posts_list">
                            <ul class="nav nav-collapse" style="list-style: none">
                                <li>
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
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#campaigns_list">
                            <i class="fas fa-bullhorn"></i>
                            <p>Campaigns</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="campaigns_list">
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
                  <li class="nav-item">
                      <a data-bs-toggle="collapse" href="#banners_list">
                          <i class="fas fa-gear"></i>
                          <p>Settings</p>
                          <span class="caret"></span>
                      </a>
                      <div class="collapse" id="banners_list">
                          <ul class="nav nav-collapse" style="list-style: none">
                              <li>
                                  <a href="{{ route('settings') }}">
                                      <span class="ms-5">View Setting</span>
                                  </a>
                              </li>


                          </ul>
                      </div>
                  </li>
              
                    


              </ul>
          </div>
      </div>
  </div>
  <!-- End Sidebar -->
