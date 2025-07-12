<!-- Project/Task Page Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-4 pb-3 mb-4 border-bottom">
    <!-- Left Side Title -->
    <div>
        <h1 class="h2">Task Management</h1>
        <p class="mb-0 text-muted">Manage Tasks and their associated projects</p>
    </div>

    <!-- Right Side Buttons -->
    <div class="btn-toolbar mb-2 mb-md-0">
        <!-- New Task Button -->
        <div class="btn-group me-2">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newTaskModal">
                <i class="fas fa-plus me-1"></i> New Task
            </button>
        </div>

        <!-- Export Dropdown Button -->
        <div class="btn-group">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-download me-1"></i> Export
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="exportDropdown">
                <li><a class="dropdown-item" href="#" onclick="invoiceManager.handleExport('csv')"><i class="fas fa-file-csv me-2"></i> CSV</a></li>
                <li><a class="dropdown-item" href="#" onclick="invoiceManager.handleExport('excel')"><i class="fas fa-file-excel me-2"></i> Excel</a></li>
                <li><a class="dropdown-item" href="#" onclick="invoiceManager.handleExport('pdf')"><i class="fas fa-file-pdf me-2"></i> PDF</a></li>
            </ul>
        </div>
    </div>
</div>


<!-- Project Tabs -->
<ul class="nav nav-tabs mb-4" id="projectTabs" role="tablist">
    
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="tasks-tab" data-bs-toggle="tab" data-bs-target="#tasks" type="button" role="tab">
            <i class="fas fa-tasks me-1"></i> All Tasks
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="projects-tab" data-bs-toggle="tab" data-bs-target="#projects" type="button"
            role="tab">
            <i class="fas fa-project-diagram me-1"></i> Projects
        </button>
    </li>
    <!-- <li class="nav-item" role="presentation">
        <button class="nav-link" id="my-tasks-tab" data-bs-toggle="tab" data-bs-target="#my-tasks" type="button"
            role="tab">
            <i class="fas fa-user-check me-1"></i> My Tasks
        </button>
    </li> -->
</ul>

<!-- Project/Task Content -->
<div class="tab-content" id="projectTabsContent">
    <!-- Projects Tab -->
    <div class="tab-pane fade show" id="projects" role="tabpanel">
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="projectsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Project</th>
                                <th>Client</th>
                                <th>Type</th>
                                <th>Tasks</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- All Tasks Tab -->
    <div class="tab-pane fade show active" id="tasks" role="tabpanel">
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <!-- Filter/Search by Project -->
                <div class="p-3 pb-0">
                    <div class="row">
                        <div class="col-md-6 col-lg-4">
                            <label for="filterProject" class="form-label mb-1">Filter by Project</label>
                            <select class="form-select" id="filterProject">
                                <option value="">All Projects</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tasksTable">
                        <thead class="table-light">
                            <tr>
                                <th>Task</th>
                                <th>Project</th>
                                <th>Date</th>
                                <th>Hours</th>
                                <th>Assignee</th>
                                <th>Status</th>
                                <th>ClickUp Link</th>
                                <th>Task Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- My Tasks Tab -->
    <div class="tab-pane fade" id="my-tasks" role="tabpanel">
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="myTasksTable">
                        <thead class="table-light">
                            <tr>
                                <th>Task</th>
                                <th>Project</th>
                                <th>Date</th>
                                <th>Hours</th>
                                <th>Status</th>
                                <th>ClickUp Link</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Task Modal -->
<div class="modal fade" id="newTaskModal" tabindex="-1" aria-labelledby="newTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="newTaskModalLabel">Create New Task</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="taskForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="taskTitle" class="form-label">Task Title</label>
                            <input type="text" class="form-control" id="taskTitle" name="title"
                                placeholder="Enter task title" required>
                        </div>

                        <div class="col-md-6">
                            <label for="taskProject" class="form-label">Project</label>
                            <select class="form-select" id="taskProject" name="project_id" required>
                                <option value="" selected disabled>Loading projects...</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="taskDate" class="form-label">Date</label>
                            <input type="date" class="form-control" id="taskDate" name="task_date" required>
                        </div>

                        <div class="col-md-6">
                            <label for="taskHours" class="form-label">Estimated Hours</label>
                            <input type="number" class="form-control" id="taskHours" name="hours" step="0.5" min="0.5"
                                placeholder="0.0" required>
                        </div>

                        <div class="col-md-6">
                            <label for="taskAssignee" class="form-label">Assignee</label>
                            <select class="form-select" id="taskAssignee" name="assignee_id" required>
                                <option value="" selected disabled>Loading users...</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="taskStatus" class="form-label">Status</label>
                            <select class="form-select" id="taskStatus" name="status" required>
                                <option value="Pending" selected>Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="taskDetails" class="form-label">Task Details</label>
                            <textarea class="form-control" id="taskDetails" name="details" rows="3"
                                placeholder="Detailed description of the task"></textarea>
                        </div>

                        <div class="col-12">
                            <label for="clickupLink" class="form-label">ClickUp Link</label>
                            <input type="url" class="form-control" id="clickupLink" name="clickup_link"
                                placeholder="https://app.clickup.com/t/xxxxxx">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveTask">Create Task</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Editing Tasks -->
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editTaskForm">
                    <input type="hidden" id="editTaskId" name="task_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="editTaskTitle" class="form-label">Task Title</label>
                            <input type="text" class="form-control" id="editTaskTitle" name="title" required>
                        </div>

                        <div class="col-md-6">
                            <label for="editTaskProject" class="form-label">Project</label>
                            <select class="form-select" id="editTaskProject" name="project_id" required>
                                <option value="" selected disabled>Loading projects...</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="editTaskDate" class="form-label">Date</label>
                            <input type="date" class="form-control" id="editTaskDate" name="task_date" required>
                        </div>

                        <div class="col-md-6">
                            <label for="editTaskHours" class="form-label">Estimated Hours</label>
                            <input type="number" class="form-control" id="editTaskHours" name="hours" step="0.5"
                                min="0.5" required>
                        </div>

                        <div class="col-md-6">
                            <label for="editTaskAssignee" class="form-label">Assignee</label>
                            <select class="form-select" id="editTaskAssignee" name="assignee_id" required>
                                <option value="" selected disabled>Loading users...</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="editTaskStatus" class="form-label">Status</label>
                            <select class="form-select" id="editTaskStatus" name="status" required>
                                <option value="Pending">Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="editTaskDetails" class="form-label">Task Details</label>
                            <textarea class="form-control" id="editTaskDetails" name="details" rows="3"></textarea>
                        </div>

                        <div class="col-12">
                            <label for="editClickupLink" class="form-label">ClickUp Link</label>
                            <input type="url" class="form-control" id="editClickupLink" name="clickup_link">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateTask()">Update Task</button>
            </div>
        </div>
    </div>
</div>

<!-- Include SweetAlert CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
    .nav-tabs {
        border-bottom: 1px solid #e9ecef;
    }

    .nav-tabs .nav-link {
        color: #6c757d;
        border: none;
        padding: 0.75rem 1.25rem;
        font-weight: 500;
        border-bottom: 3px solid transparent;
    }

    .nav-tabs .nav-link:hover {
        color: #3a4f8a;
        border-bottom-color: #dee2e6;
    }

    .nav-tabs .nav-link.active {
        color: #3a4f8a;
        background-color: transparent;
        border-bottom-color: #3a4f8a;
    }

    .avatar {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        font-weight: 600;
    }

    /* Add to the style section */
    .task-status-select {
        width: 120px;
        display: inline-block;
        cursor: pointer;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    .task-status-select option {
        padding: 0.5rem;
    }

    /* Color the dropdown based on status */
    .task-status-select[value="Pending"] {
        background-color: rgba(255, 193, 7, 0.1);
        border-color: #ffc107;
    }

    .task-status-select[value="In Progress"] {
        background-color: rgba(58, 79, 138, 0.1);
        border-color: #3a4f8a;
    }

    .task-status-select[value="Completed"] {
        background-color: rgba(40, 167, 69, 0.1);
        border-color: #28a745;
    }

    .avatar-sm {
        width: 24px;
        height: 24px;
        font-size: 0.75rem;
    }

    .avatar-group .avatar {
        margin-right: -10px;
        border: 2px solid #fff;
        position: relative;
    }

    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
    }

    .table th {
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #6c757d;
        border-top: none;
    }

    .table td {
        vertical-align: middle;
    }

    .card {
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border-radius: 10px;
    }

    .modal-header {
        border-radius: 0;
        border-bottom: none;
    }

    .modal-content {
        border: none;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        border: 1px solid #e9ecef;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #3a4f8a;
        box-shadow: 0 0 0 0.25rem rgba(58, 79, 138, 0.25);
    }

    .btn-primary {
        background-color: #3a4f8a;
        border-color: #3a4f8a;
    }

    .btn-primary:hover {
        background-color: #2c3d6b;
        border-color: #2c3d6b;
    }

    .btn-outline-secondary:hover {
        color: #3a4f8a;
        border-color: #3a4f8a;
    }

    .progress {
        background-color: #f1f3f5;
    }

    @media (max-width: 768px) {
        .nav-tabs .nav-link {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    }

    .task-status-select:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        background-color: #f8f9fa;
    }
</style>

<script>

// Make sure invoiceManager is properly defined as an object
const invoiceManager = {
    filters: {}, // Make sure this is defined with your actual filters
    
    handleExport: function(exportType) {
        Swal.fire({
            title: 'Preparing Export',
            html: 'Please wait while we prepare your export...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
                
                // Prepare the data to send
                const exportData = {
                    exportType: exportType,
                    filters: this.filters
                };
                
                fetch('ajax_helpers/export_taks.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(exportData)
                })
                .then(response => {
                    if (exportType === 'pdf') {
                        // For PDF, we return HTML that will trigger print dialog
                        return response.text().then(html => {
                            // Open a new window with the HTML
                            const printWindow = window.open('', '_blank');
                            printWindow.document.write(html);
                            printWindow.document.close();
                            Swal.close();
                        });
                    } else {
                        // For CSV and Excel, handle as blob
                        return response.blob().then(blob => {
                            // Create a download link
                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.href = url;
                            
                            // Set appropriate file extension
                            const extension = exportType === 'excel' ? 'xls' : exportType; // Fixed typo from 'excel' to 'excel'
                            a.download = `tasks_export.${extension}`;
                            
                            document.body.appendChild(a);
                            a.click();
                            
                            // Clean up
                            window.URL.revokeObjectURL(url);
                            a.remove();
                            Swal.close();
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Export Failed',
                        text: error.message || 'An error occurred during export',
                        confirmButtonColor: '#3085d6',
                    });
                });
            }
        });
    }
};
    // Main Initialization Function
    document.addEventListener('DOMContentLoaded', function() {
        // Load initial data
        loadProjects();
        loadTasks();

        // New Task Modal Event Listeners
        const newTaskModal = document.getElementById('newTaskModal');
        if (newTaskModal) {
            newTaskModal.addEventListener('show.bs.modal', function() {
                loadProjectOptions();
                loadUserOptions();
            });
        }

        // Edit Task Modal Event Listeners
        const editTaskModal = document.getElementById('editTaskModal');
        if (editTaskModal) {
            editTaskModal.addEventListener('show.bs.modal', function() {
                loadProjectOptions('editTaskProject');
                loadUserOptions('editTaskAssignee');
            });
        }

        // Save Task Button Handler
        const saveTaskBtn = document.getElementById('saveTask');
        if (saveTaskBtn) {
            saveTaskBtn.addEventListener('click', function() {
                const taskForm = document.getElementById('taskForm');
                if (taskForm.checkValidity()) {
                    saveTask();
                } else {
                    taskForm.reportValidity();
                }
            });
        }

        // Tab Change Event Handlers
        const tabEls = document.querySelectorAll('button[data-bs-toggle="tab"]');
        tabEls.forEach(tabEl => {
            tabEl.addEventListener('shown.bs.tab', function(event) {
                const target = event.target.getAttribute('data-bs-target');
                if (target === '#tasks') {
                    loadTasks();
                } else if (target === '#my-tasks') {
                    loadMyTasks();
                } else if (target === '#projects') {
                    loadProjects();
                }
            });
        });

        // Populate filterProject dropdown
        fetch('ajax_helpers/task_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'action=get_projects'
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && Array.isArray(data.data)) {
                    const filterSelect = document.getElementById('filterProject');
                    data.data.forEach(project => {
                        const opt = document.createElement('option');
                        opt.value = project.id;
                        opt.textContent = project.name;
                        filterSelect.appendChild(opt);
                    });
                }
            });

        // Add filter event
        const filterProject = document.getElementById('filterProject');
        if (filterProject) {
            filterProject.addEventListener('change', function() {
                loadTasks();
            });
        }
    });

    // Load Projects Data
    async function loadProjects() {
        try {
            const response = await fetch('ajax_helpers/task_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=get_projects'
            });
            const data = await response.json();

            if (!data.success) {
                throw new Error(data.error || 'Failed to load projects');
            }

            const tbody = document.querySelector('#projectsTable tbody');
            tbody.innerHTML = '';

            if (data.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4">No projects found</td></tr>';
                return;
            }

            data.data.forEach(project => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <a href="#" class="text-primary fw-bold">${project.name}</a>
                        <small class="text-muted d-block">${project.from_company}</small>
                    </td>
                    <td>${project.to_client}</td>
                    <td>${project.type}</td>
                    <td>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: ${(project.completed_tasks / project.task_count) * 100 || 0}%" 
                                 aria-valuenow="${project.completed_tasks}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="${project.task_count}">
                            </div>
                        </div>
                        <small class="text-muted">${project.completed_tasks} of ${project.task_count} tasks</small>
                    </td>
                    <td><span class="badge ${project.completed_tasks == project.task_count ? 'bg-success' : 'bg-primary'}">
                        ${project.completed_tasks == project.task_count ? 'Completed' : 'In Progress'}
                    </span></td>

                    <td>
                        <div class="d-flex gap-2">
                            <a href="#" 
                               class="btn btn-outline-secondary p-0 d-flex align-items-center justify-content-center action-view-project" 
                               style="width:32px;height:32px;border-radius:6px;border:1px solid #dee2e6;" 
                               title="View Project" onclick="viewProject(${project.id})">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="#" 
                               class="btn btn-outline-danger p-0 d-flex align-items-center justify-content-center action-delete-project" 
                               style="width:32px;height:32px;border-radius:6px;border:1px solid #dc3545;" 
                               title="Delete Project" onclick="deleteProject(${project.id})">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
        } catch (error) {
            showError('Error loading projects: ' + error.message);
        }
    }

    // Load All Tasks Data
    async function loadTasks() {
        try {
            const filterProject = document.getElementById('filterProject');
            const projectId = filterProject ? filterProject.value : '';
            let body = 'action=get_tasks';
            if (projectId) {
                body += `&project_id=${encodeURIComponent(projectId)}`;
            }
            const response = await fetch('ajax_helpers/task_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: body
            });
            const data = await response.json();

            if (!data.success) {
                throw new Error(data.error || 'Failed to load tasks');
            }

            const tbody = document.querySelector('#tasksTable tbody');
            tbody.innerHTML = '';

            if (data.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center py-4">No tasks found</td></tr>';
                return;
            }

            data.data.forEach(task => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <a href="#" class="text-primary fw-bold">
                            ${task.title ? task.title.substring(0, 30) : ''}
                            ${task.title && task.title.length > 30 ? '...' : ''}
                        </a>
                        <p class="mb-0 text-muted small">Details:
                            ${task.details ? task.details.substring(0, 50) : ''}
                            ${task.details && task.details.length > 50 ? '...' : ''}
                        </p>
                        <p class="mb-0 text-muted small">Created: ${new Date(task.created_at).toLocaleDateString()}</p>
                    </td>
                    <td>${task.project_name || 'No project'}</td>
                    <td>${new Date(task.task_date).toLocaleDateString()}</td>
                    <td>${task.hours || '0'}</td>
                    <td>
                        <span class="avatar avatar-sm rounded-circle bg-primary text-white">${task.assignee_initials}</span>
                        <span class="ms-2">${task.assignee_name}</span>
                    </td>
                    <td><span class="badge ${getStatusClass(task.status)}">${task.status}</span></td>
                    <td>
                        ${task.clickup_link ? `<a href="${task.clickup_link}" target="_blank" class="text-info">View</a>` : 'No link'}
                    </td>
                   <td>
        <select class="form-select form-select-sm task-status-select" 
                data-task-id="${task.id}" 
                data-previous-value="${task.status}"
                onchange="updateTaskStatus(${task.id}, this.value)">
            <option value="Pending" ${task.status === 'Pending' ? 'selected' : ''}>Pending</option>
            <option value="In Progress" ${task.status === 'In Progress' ? 'selected' : ''}>In Progress</option>
            <option value="Completed" ${task.status === 'Completed' ? 'selected' : ''}>Completed</option>
        </select>
    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="#" 
                               class="btn btn-outline-secondary p-0 d-flex align-items-center justify-content-center action-view" 
                               style="width:32px;height:32px;border-radius:6px;border:1px solid #dee2e6;" 
                               title="View" onclick="viewTask(${task.id})">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="#" 
                               class="btn btn-outline-primary p-0 d-flex align-items-center justify-content-center action-edit" 
                               style="width:32px;height:32px;border-radius:6px;border:1px solid #3a4f8a;" 
                               title="Edit" onclick="editTask(${task.id})">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#" 
                               class="btn btn-outline-danger p-0 d-flex align-items-center justify-content-center action-delete" 
                               style="width:32px;height:32px;border-radius:6px;border:1px solid #dc3545;" 
                               title="Delete" onclick="deleteTask(${task.id})">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
        } catch (error) {
            showError('Error loading tasks: ' + error.message);
        }
    }

    // Load Current User's Tasks
    async function loadMyTasks() {
        try {
            const response = await fetch('ajax_helpers/task_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=get_my_tasks'
            });
            const data = await response.json();

            if (!data.success || !data.tasks) {
                throw new Error(data.error || 'No tasks data received');
            }

            const tbody = document.querySelector('#myTasksTable tbody');
            tbody.innerHTML = '';

            if (data.tasks.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4">No tasks assigned to you</td></tr>';
                return;
            }

            data.tasks.forEach(task => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <a href="#" class="text-primary fw-bold">${task.title}</a>
                        <p class="mb-0 text-muted small">${task.details || 'No description'}</p>
                    </td>
                    <td>${task.project_name || 'No project'}</td>
                    <td>${task.task_date}</td>
                    <td>${task.hours || '0'}</td>
                    <td><span class="badge ${getStatusClass(task.status)}">${formatStatus(task.status)}</span></td>
                    <td>
                        ${task.clickup_link ? `<a href="${task.clickup_link}" target="_blank" class="text-info">View in ClickUp</a>` : 'No link'}
                    </td>
                    <td>
                        <button class="btn btn-sm btn-success me-1" onclick="completeTask(${task.id})">Complete</button>
                        <button class="btn btn-sm btn-outline-secondary" onclick="logTime(${task.id})">Log Time</button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        } catch (error) {
            showError('Error loading your tasks: ' + error.message);
        }
    }

    // Load Project Options for Select Dropdowns
    async function loadProjectOptions(targetId = 'taskProject') {
        const select = document.getElementById(targetId);
        select.innerHTML = '<option value="" selected disabled>Loading projects...</option>';

        try {
            const response = await fetch('ajax_helpers/task_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=get_projects'
            });
            const data = await response.json();

            if (!data.success) {
                throw new Error(data.error || 'Failed to load projects');
            }

            select.innerHTML = '<option value="" selected disabled>Select project</option>';
            data.data.forEach(project => {
                const option = document.createElement('option');
                option.value = project.id;
                option.textContent = project.name;
                select.appendChild(option);
            });
        } catch (error) {
            select.innerHTML = '<option value="" selected disabled>Error loading projects</option>';
            showError('Error loading projects: ' + error.message);
        }
    }

    // Load User Options for Select Dropdowns
    async function loadUserOptions(targetId = 'taskAssignee') {
        const select = document.getElementById(targetId);
        select.innerHTML = '<option value="" selected disabled>Loading users...</option>';

        try {
            const response = await fetch('ajax_helpers/task_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=get_users'
            });
            const data = await response.json();

            if (!data.success) {
                throw new Error(data.error || 'Failed to load users');
            }

            select.innerHTML = '<option value="" selected disabled>Select assignee</option>';
            data.users.forEach(user => {
                const option = document.createElement('option');
                option.value = user.user_id;
                option.textContent = user.name;
                select.appendChild(option);
            });
        } catch (error) {
            select.innerHTML = '<option value="" selected disabled>Error loading users</option>';
            showError('Error loading users: ' + error.message);
        }
    }

    // Save New Task
    async function saveTask() {
        const form = document.getElementById('taskForm');
        const formData = new FormData(form);
        formData.append('action', 'create_task');

        // Validate required fields
        if (!formData.get('title') || !formData.get('project_id') || !formData.get('task_date') ||
            !formData.get('assignee_id') || !formData.get('status')) {
            showError('Please fill all required fields');
            return;
        }

        try {
            const response = await fetch('ajax_helpers/task_handler.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.error || 'Failed to create task');
            }

            const modal = bootstrap.Modal.getInstance(document.getElementById('newTaskModal'));
            modal.hide();

            Swal.fire({
                title: 'Success!',
                text: 'Task created successfully!',
                icon: 'success',
                confirmButtonColor: '#3a4f8a',
                timer: 2000,
                timerProgressBar: true
            });

            form.reset();
            loadTasks();
        } catch (error) {
            showError('Error creating task: ' + error.message);
        }
    }

    

    // Delete Task
    async function deleteTask(taskId) {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3a4f8a',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        });

        if (!result.isConfirmed) return;

        try {
            const response = await fetch('ajax_helpers/task_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=delete_task&task_id=${taskId}`
            });
            const data = await response.json();

            if (!data.success) {
                throw new Error(data.error || 'Failed to delete task');
            }

            Swal.fire({
                title: 'Deleted!',
                text: 'Task has been deleted.',
                icon: 'success',
                confirmButtonColor: '#3a4f8a',
                timer: 2000,
                timerProgressBar: true
            });

            loadTasks();
        } catch (error) {
            showError('Error deleting task: ' + error.message);
        }
    }

    // Edit Task - Load Data into Modal
    async function editTask(taskId) {
        try {
            // First fetch task details
            const response = await fetch('ajax_helpers/task_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=get_task_details&task_id=${taskId}`
            });
            const data = await response.json();

            if (!data.success) {
                throw new Error(data.error || 'Failed to load task details');
            }

            const task = data.data.task;

            // Populate the edit modal
            document.getElementById('editTaskTitle').value = task.title;
            document.getElementById('editTaskProject').value = task.project_id;
            document.getElementById('editTaskDate').value = task.task_date;
            document.getElementById('editTaskHours').value = task.hours;
            document.getElementById('editTaskAssignee').value = task.assignee_id;
            document.getElementById('editTaskStatus').value = task.status;
            document.getElementById('editTaskDetails').value = task.details || '';
            document.getElementById('editClickupLink').value = task.clickup_link || '';
            document.getElementById('editTaskId').value = task.id;

            // Show the modal
            const editModal = new bootstrap.Modal(document.getElementById('editTaskModal'));
            editModal.show();
        } catch (error) {
            showError('Error loading task details: ' + error.message);
        }
    }

    // Update Task
    async function updateTask() {
        const form = document.getElementById('editTaskForm');
        const formData = new FormData(form);
        formData.append('action', 'update_task');

        try {
            const response = await fetch('ajax_helpers/task_handler.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.error || 'Failed to update task');
            }

            const modal = bootstrap.Modal.getInstance(document.getElementById('editTaskModal'));
            modal.hide();

            Swal.fire({
                title: 'Success!',
                text: 'Task updated successfully!',
                icon: 'success',
                confirmButtonColor: '#3a4f8a',
                timer: 2000,
                timerProgressBar: true
            });

            loadTasks();
        } catch (error) {
            showError('Error updating task: ' + error.message);
        }
    }

    // Complete Task
    async function completeTask(taskId) {
        try {
            const response = await fetch('ajax_helpers/task_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=complete_task&task_id=${taskId}`
            });
            const data = await response.json();

            if (!data.success) {
                throw new Error(data.error || 'Failed to complete task');
            }

            Swal.fire({
                title: 'Task Completed!',
                text: 'The task has been marked as completed.',
                icon: 'success',
                confirmButtonColor: '#3a4f8a',
                timer: 2000,
                timerProgressBar: true
            });

            loadMyTasks();
        } catch (error) {
            showError('Error completing task: ' + error.message);
        }
    }

    // Log Time for Task
    async function logTime(taskId) {
        const {
            value: hours
        } = await Swal.fire({
            title: 'Log Time',
            text: 'Enter hours spent on this task:',
            input: 'number',
            inputAttributes: {
                step: '0.25',
                min: '0.25'
            },
            inputPlaceholder: 'Enter hours (e.g. 2.5)',
            showCancelButton: true,
            confirmButtonColor: '#3a4f8a',
            inputValidator: (value) => {
                if (!value) {
                    return 'You need to enter hours!';
                }
                if (parseFloat(value) <= 0) {
                    return 'Hours must be greater than 0';
                }
            }
        });

        if (hours) {
            try {
                const response = await fetch('ajax_helpers/task_handler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=log_time&task_id=${taskId}&hours=${hours}`
                });
                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.error || 'Failed to log time');
                }

                Swal.fire({
                    title: 'Time Logged!',
                    text: `${hours} hours have been logged for this task.`,
                    icon: 'success',
                    confirmButtonColor: '#3a4f8a',
                    timer: 2000,
                    timerProgressBar: true
                });

                loadMyTasks();
            } catch (error) {
                showError('Error logging time: ' + error.message);
            }
        }
    }

    // Update the updateTaskStatus function
    async function updateTaskStatus(taskId, newStatus) {
        try {
            const selectElement = document.querySelector(`.task-status-select[data-task-id="${taskId}"]`);
            selectElement.disabled = true; // Disable the dropdown during update

            const response = await fetch('ajax_helpers/task_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=update_task_status&task_id=${taskId}&status=${encodeURIComponent(newStatus)}`
            });

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.error || 'Failed to update task status');
            }

            // Show success message and reload the page after a short delay
            Swal.fire({
                title: 'Success!',
                text: 'Task status updated successfully!',
                icon: 'success',
                confirmButtonColor: '#3a4f8a',
                timer: 1000,
                timerProgressBar: true,
                didClose: () => {
                    location.reload(); // Reload the page after the alert closes
                }
            });

        } catch (error) {
            showError('Error updating task status: ' + error.message);
            // Re-enable the dropdown and revert to previous value
            const select = document.querySelector(`.task-status-select[data-task-id="${taskId}"]`);
            if (select) {
                select.disabled = false;
                select.value = select.getAttribute('data-previous-value');
            }
        }
    }
    // Delete Project
    async function deleteProject(projectId) {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "This will delete the project and all associated tasks!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3a4f8a',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        });

        if (!result.isConfirmed) return;

        try {
            const response = await fetch('ajax_helpers/task_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=delete_project&project_id=${projectId}`
            });
            const data = await response.json();

            if (!data.success) {
                throw new Error(data.error || 'Failed to delete project');
            }

            Swal.fire({
                title: 'Deleted!',
                text: 'Project has been deleted.',
                icon: 'success',
                confirmButtonColor: '#3a4f8a',
                timer: 2000,
                timerProgressBar: true
            });

            loadProjects();
        } catch (error) {
            showError('Error deleting project: ' + error.message);
        }
    }

    // Helper Functions
    function getStatusClass(status) {
        switch (status.toLowerCase()) {
            case 'pending':
                return 'bg-warning';
            case 'in progress':
                return 'bg-primary';
            case 'completed':
                return 'bg-success';
            default:
                return 'bg-secondary';
        }
    }

    function formatStatus(status) {
        return status.split('-').map(word =>
            word.charAt(0).toUpperCase() + word.slice(1)
        ).join(' ');
    }

    function showAlert(message, type = 'success') {
        Swal.fire({
            title: type === 'success' ? 'Success!' : 'Error!',
            text: message,
            icon: type,
            confirmButtonColor: '#3a4f8a',
            timer: 3000,
            timerProgressBar: true
        });
    }

    function showError(message) {
        showAlert(message, 'error');
    }

    function viewProject(id) {
        // Implement view project modal or redirect
        Swal.fire({
            title: 'View Project',
            text: 'Project details would be shown here',
            icon: 'info',
            confirmButtonColor: '#3a4f8a'
        });
    }

    function viewTask(id) {
        // Implement view task modal or redirect
        Swal.fire({
            title: 'View Task',
            text: 'Task details would be shown here',
            icon: 'info',
            confirmButtonColor: '#3a4f8a'
        });
    }
</script>