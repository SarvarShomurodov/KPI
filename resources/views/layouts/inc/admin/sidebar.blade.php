<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
      @if(auth()->user() && auth()->user()->hasRole('Super Admin'))
        <li class="nav-item nav-category">Accounts</li>
        @canany(['create-user', 'edit-user', 'delete-user'])
        <li class="nav-item {{ Request::is('admin/users*') ? 'active' : ''  }}">
          <a class="nav-link" href="{{ route('admin.users.index') }}">
            <i class="menu-icon mdi mdi-account"></i>
            <span class="menu-title">CustomUsers</span>
          </a>
        </li>
        @endcanany  
        @canany(['create-project', 'edit-project', 'delete-project'])
          <li class="nav-item {{ Request::is('admin/projects*') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('admin.projects.index') }}">
            <i class="menu-icon mdi mdi-folder"></i>
            <span class="menu-title">Projects</span>
          </a>
          </li>
        @endcanany  
        <li class="nav-item nav-category">Avtorizatsiya</li>
        @canany(['create-role', 'edit-role', 'delete-role'])
        <li class="nav-item {{ Request::is('admin/roles*') ? 'active' : '' }}"> 
          <a class="nav-link" href="{{ route('admin.roles.index') }}">
              <i class="menu-icon mdi mdi-account-key"></i>
              <span class="menu-title">Manage Roles</span>
          </a>
        </li>
        @endcanany
        <li class="nav-item nav-category">Tasks</li>
        @canany(['create-task', 'edit-task', 'delete-task'])
        <li class="nav-item {{ Request::is('admin/tasks*') ? 'active' : '' }}"> 
          <a class="nav-link" href="{{ route('admin.tasks.index') }}">
              <i class="menu-icon mdi mdi-check-circle"></i>
              <span class="menu-title">Tasks</span>
          </a>
        </li>
      @endcanany
      
      @canany(['create-subtask', 'edit-subtask', 'delete-subtask'])
        <li class="nav-item {{ Request::is('admin/subtasks*') ? 'active' : '' }}"> 
          <a class="nav-link" href="{{ route('admin.subtasks.index') }}">
              <i class="menu-icon mdi mdi-checkbox-blank-circle-outline"></i>
              <span class="menu-title">Sub Tasks</span>
          </a>
        </li>
      @endcanany
        @canany(['create-subtask', 'edit-subtask', 'delete-subtask'])
          <li class="nav-item {{ Request::is('admin.task_assignments*') ? 'active' : '' }}"> 
            <a class="nav-link" href="{{ route('admin.task_assignments.index') }}">
                <i class="menu-icon mdi mdi-account-multiple"></i>
                <span class="menu-title">Task Assignees</span>
            </a>
          </li>
        @endcanany
      @endif
      @if(auth()->user() && auth()->user()->hasRole('Admin'))
      <li class="nav-item nav-category">Sahifalar</li>
        <li class="nav-item">
          <a class="nav-link" href="index.html">
            <i class="mdi mdi-grid-large menu-icon"></i>
            <span class="menu-title">Dashboard</span>
          </a>
        </li>
        @canany(['create-subtask', 'edit-subtask', 'delete-subtask'])
          {{-- <li class="nav-item {{ Request::is('admin.task_assignments*') ? 'active' : '' }}"> 
            <a class="nav-link" href="{{ route('admin.task_assignments.index') }}">
                <i class="menu-icon mdi mdi-account-multiple"></i>
                <span class="menu-title">Task Assignees</span>
            </a>
          </li> --}}
          <li class="nav-item {{ Request::is('tasks*') ? 'active' : '' }}"> 
            <a class="nav-link" href="{{ url('tasks') }}">
                <i class="menu-icon mdi mdi-thumb-up"></i>
                <span class="menu-title">KPI baholash</span>
            </a>
          </li>
        @endcanany
      @endif
    </ul>
  </nav>