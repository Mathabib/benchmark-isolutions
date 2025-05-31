<!--begin::Sidebar-->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <!--begin::Sidebar Brand-->
            <div class="sidebar-brand">
            <!--begin::Brand Link-->
            <a href="./index.html" class="brand-link">
                <!--begin::Brand Image-->
                <img
                src="{{ asset('assets/image/resindo-logo.jpg') }}"
                width="70px"
                alt="resindo logo"
                class="brand-image opacity-75 shadow"
                />
                <!--end::Brand Image-->
                <!--begin::Brand Text-->
                <span class="brand-text fw-light">Task Manager</span>
                <!--end::Brand Text-->
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
                        <a href="#" class="nav-link active">
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
                
                </ul>
                <!--end::Sidebar Menu-->
            </nav>
            </div>
            <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->