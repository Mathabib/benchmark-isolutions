<!--begin::Sidebar-->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <!--begin::Sidebar Brand-->
            <div class="sidebar-brand">
            <!--begin::Brand Link-->
            <a href="./index.html" class="brand-link d-flex align-items-center justify-content-between px-3">
                <!--begin::Left Logo-->
                <img
                  src="{{ asset('assets/image/resindo-logo.jpg') }}"
                  width="70"
                  alt="Resindo Logo"
                  class="brand-image opacity-75 shadow"
                />
            
                <!--begin::Right Logo-->
                <img
                  src="{{ asset('assets/image/benchmark-logo.png') }}"
                  width="70"
                  alt="Benchmark Logo"
                  class="brand-image opacity-75 shadow"
                />
            </a>
            <!--end::Brand Link-->
            </div>
            <!--end::Sidebar Brand-->
            <!--begin::Sidebar Wrapper-->
            <div class="sidebar-wrapper">
            <nav class="mt-2">
                <!--begin::Sidebar Menu-->
                <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link active">
                            <i class="nav-icon bi bi-speedometer"></i>
                            <p>Dashboard</p>
                        </a>
                    </li> 
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon bi bi-box-seam-fill"></i>
                            <p>
                                Projects
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @foreach($projects as $project)
                                <li class="nav-item">
                                    <a href="{{ route('projects.show', $project->id) }}" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>{{ $project->nama }}</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon bi bi-box-seam-fill"></i>
                            <p>
                                Settings
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                          
                                <li class="nav-item">
                                    <a href="{{ route('projects.index2', $project->id) }}" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Projects</p>
                                    </a>
                                </li>

                                 <li class="nav-item">
                                    <a href="{{ route('users.index', $project->id) }}" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Users</p>
                                    </a>
                                </li>
                           
                        </ul>
                    </li>
                </ul>
                <!--end::Sidebar Menu-->
            </nav>
            </div>
            <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->

      <script>
        console.log('hallo dari asside menu kebaca gak ya')
      </script>