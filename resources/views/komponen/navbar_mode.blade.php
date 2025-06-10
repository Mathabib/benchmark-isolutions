<!--begin::Header-->
<nav class="app-header navbar navbar-expand bg-body mt-5" style="z-index: 10;">
    <!--begin::Container-->
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item d-none d-md-block"><a href="{{ route('projects.show', $project->id) }}" class="nav-link">BOARD</a></li>
            <li class="nav-item d-none d-md-block"><a href="{{ route('projects.list', $project->id) }}" class="nav-link">LIST</a></li>
            <li class="nav-item d-none d-md-block"><a href="{{ route('projects.gantt', $project->id) }}" class="nav-link">GANTT</a></li>
        </ul>
    </div>
    <!--end::Container-->
</nav>
<!--end::Header-->
