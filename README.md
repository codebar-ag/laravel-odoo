
|---|---|---|---|---|---|
| **Discovery / Health** |||||
| `databases()` | `POST` | `/web/database/list` | — | — | ToDO |
| `health()` | `GET` | `/web/health` | — | — | ToDO |
| `version()` | `GET` | `/web/version` | — | — | ToDO |
| **Auth** |||||
| `auth()` | `POST` | `/web/session/authenticate` | — | — | ToDo |
| `logout()` | `POST` | `/web/session/destroy` | — | — | ToDo |
| **Employee** |||||
| `getEmployeeByUserId()` | `POST` | `/web/dataset/call_kw` | `hr.employee` | `search_read` | ToDo |
| **Schema / Permissions** |||||
| `getFields()` | `POST` | `/web/dataset/call_kw` | `{model}` | `fields_get` | ToDO |
| `getAllFields()` | `POST` | `/web/dataset/call_kw` | `account.analytic.line` | `fields_get` | ToDO |
| `checkPermissions()` | `POST` | `/web/dataset/call_kw` | `{model}` | `has_access` | ToDO |
| **Projects & Tasks** |||||
| `getProjects()` | `POST` | `/web/dataset/call_kw` | `project.project` | `search_read` | ToDO |
| `getAllTasks()` | `POST` | `/web/dataset/call_kw` | `project.task` | `search_read` | ToDO |
| `getTasksByProject()` | `POST` | `/web/dataset/call_kw` | `project.task` | `search_read` | ToDO |
| **Timesheets** |||||
| `getTimesheetEntries()` | `POST` | `/web/dataset/call_kw` | `account.analytic.line` | `search_read` | ToDO |
| `getTimesheetEntriesLastDays()` | `POST` | `/web/dataset/call_kw` | `account.analytic.line` | `search_read` | ToDO |
| `readTimesheet()` | `POST` | `/web/dataset/call_kw` | `account.analytic.line` | `read` | ToDO |
| `createTimesheet()` | `POST` | `/web/dataset/call_kw` | `account.analytic.line` | `create` | ToDO |
| `updateTimesheet()` | `POST` | `/web/dataset/call_kw` | `account.analytic.line` | `write` | ToDO |
| `deleteTimesheet()` | `POST` | `/web/dataset/call_kw` | `account.analytic.line` | `unlink` | ToDO |
| **Sync (parallel pool)** |||||
| `syncAll()` — projects | `POST` | `/web/dataset/call_kw` | `project.project` | `search_read` | ToDO |
| `syncAll()` — tasks | `POST` | `/web/dataset/call_kw` | `project.task` | `search_read` | ToDO |
| `syncAll()` — timesheets | `POST` | `/web/dataset/call_kw` | `account.analytic.line` | `search_read` | ToDO |