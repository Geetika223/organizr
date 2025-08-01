// ADD TASK START
document.addEventListener("DOMContentLoaded", () => {
    const taskForm = document.getElementById("task-form");
    const showFormBtn = document.getElementById("show-task-form");
    const taskList = document.getElementById("task-list");
    showFormBtn.addEventListener("click", () => {
        taskForm.style.display = taskForm.style.display === "none" ? "block" : "none";
    });
    function loadTasks() {
        fetch("load_tasks.php")
            .then(res => res.json())
            .then(tasks => {
                taskList.innerHTML = "";
                tasks.forEach(task => {
                    const item = document.createElement("div");
                    item.className = "task-item";
                    item.innerHTML = `
                        <h3>${task.title}</h3>
                        <p>${task.description}</p>
                        <p><small>Due: ${task.due_date}</small></p>
                        <div class="task-actions">
                            <button class="btn btn-primary" data-id="${task.id}">Edit</button>
                            <button class="btn btn-danger" data-id="${task.id}">Delete</button>
                        </div>
                    `;
                    taskList.appendChild(item);
                });
            });
    }
    taskForm.addEventListener("submit", e => {
        e.preventDefault();
        const formData = new FormData();
        formData.append("title", document.getElementById("title").value);
        formData.append("description", document.getElementById("description").value);
        formData.append("due_date", document.getElementById("due_date").value);
        fetch("add_task.php", {
            method: "POST",
            body: formData
        })
            .then(res => res.text())
            .then(response => {
                if (response.trim() === "added") {
                    alert("Task added successfully.");
                    taskForm.reset();
                    taskForm.style.display = "none";
                    loadTasks();
                } else {
                    alert("Error adding task.");
                }
            });
    });
    loadTasks();
});
// ADD TASK END
//geetika
    document.addEventListener("DOMContentLoaded", () => {
        //Sidebar Toggle 
        const toggleBtn = document.getElementById("toggle-btn");
        const sidebar = document.getElementById("sidebar");
        const mainContent = document.getElementById("main-content");

        toggleBtn?.addEventListener("click", (e) => {
            e.stopPropagation(); // prevent auto-close trigger
            sidebar?.classList.toggle("show");
            mainContent?.classList.toggle("shifted");
        });

        // Close sidebar when clicking outside
        document.addEventListener("click", (e) => {
            const clickedOutsideSidebar = !sidebar?.contains(e.target);
            const clickedOutsideToggle = !toggleBtn?.contains(e.target);
            if (clickedOutsideSidebar && clickedOutsideToggle) {
                sidebar?.classList.remove("show");
                mainContent?.classList.remove("shifted");
            }
        });

        // Dropdowns 
        document.querySelectorAll('.btn-group').forEach(group => {
            const buttons = group.querySelectorAll('.dropdown-toggle');
            const menus = group.querySelectorAll('.dropdown-menu');

            buttons.forEach((btn) => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    menus.forEach(menu => menu.style.display = 'none');

                    const targetMenu = btn.nextElementSibling;
                    if (targetMenu?.classList.contains('dropdown-menu')) {
                        targetMenu.style.display = (targetMenu.style.display === 'block') ? 'none' : 'block';
                    }
                });
            });
        });

        // Close all dropdowns on outside click
        document.addEventListener('click', () => {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.style.display = 'none';
            });
        });

              //  Sidebar "Add Task" Button
        document.getElementById('sidebar-add-task')?.addEventListener('click', (e) => {
            e.preventDefault();
            const taskForm = document.getElementById('task-form');
            if (taskForm) {
                taskForm.style.display = 'block';
                window.scrollTo({
                    top: taskForm.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });

        //  Optional: Dropdown Alert for Items 
        document.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', (e) => {
                e.stopPropagation();
                alert('Dropdown item clicked: ' + item.textContent.trim());
            });
        });
    });