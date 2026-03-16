<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Employee Create
                            <a href="../index.php" class="btn btn-danger float-end">BACK</a>
                        </h4>

                        <div class="mb-4">
                            <form action="PHP/code.php" method="post">
                                <div class="mb-3">
                                    <label>Username</label>
                                    <input type="Username" required class="form-control" name="Username">
                                </div>

                                  <div class="mb-3">
                                    <label>Password</label>
                                    <input type="password" required class="form-control" name="Password">
                                </div>

                                  <div class="mb-3">
                                    <label>Employee Email</label>
                                    <input type="email" required class="form-control" name="StudentEmail">
                                </div>
                                  <div class="mb-3">
                                    <label>Phone number</label>
                                    <input type="text" required class="form-control" name="PhoneNumber">
                                </div>
                                  <div class="mb-3">
                                    <label>Function</label>
                                    <select class="form-control" name="Function">
                                        <option value="">Select Function</option>
                                        <option value="Manager">Manager</option>
                                        <option value="Employee">Admin</option>
                                    </select>
                                </div>

                                <div class="mb3">
                                    <button type="Submit" name="save_employee" class=" btn btn-success mb-3 mt-3">Save Employee</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>