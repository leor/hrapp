# Simple HR app

## About

This is a simple application to manage Departments with Employees. 
It can generate 2 simple reports as well:
- Show all departments along with the highest salary within each department. A department with no employees should show 0 as the highest salary.
- List just those departments that have more than two employees that earn over 50k (***Note:** I interpreted this as salary >= 50000*).

***Note:** I've extended the data with Total Employees count for Report 1 and Rich Employees (with salary >= 50000) count for Report 2*

The application consists of two parts:
- API - made with Lumen Framework (without Eloquent). Uses MySQL as DB. Made with TDD methodology.
- Client - made with Create-React-App (Typescript). Uses React hooks. Interface is build with React Bootstrap.

The app wrapped into Docker container, so you have to make some steps to run it.

## Running the Docker

You need to have Docker and Docker Compose installed on your system to run this App.

### Step 1

Clone the repository to your locale:

```
git clone git@github.com:leor/hrapp.git hrapp
```

Then go into that new folder:

```
cd hrapp
```

## Step 2

Unfortunately I didn't manage to create proper `docker-compose.yml` that could install Composer dependencies 
automatically (well, I'm not the best Docker user). So you have to run Composer manually.

Go to the `api` folder:

```
cd api
```

Then run command:

```
docker run --rm -v $(pwd):/app composer install
```

This will mount official Composer Docker image to install necessary dependencies required for Api work. 
It's a time-consuming process, however we need to run it only once.

Also we need to initiate Environmental variables for Lumen. Just run command:

```
cp .env.example .env
```

## Step 3

Now get back to the project root folder:

```
cd ../
```

Run:

```
docker compose up -d
```

to run `mysql`, `nginx`, `php-fpm` and `client` containers. This can take a while, so keep patience, please.

When it's done, we have our App run. Let's check our containers:

```
docker compose ps
```

You should see information about containers including their Name, Status and Ports. 
Find `hr-app_client_*` PORTS. Usually it looks like this:

```
0.0.0.0:80->80/tcp, :::80->80/tcp
```

That means that app is available in browser on `0.0.0.0`. 
Put `0.0.0.0` to the URL bar and here we go.

***Note:** Sometimes you can get an error on Departments list request. Just wait for a while for API service to run properly.*

## Interface

### Main page

You should see the basic Bootstrap interface with menu:
- Departments - manage Departments and Employees
- Reports - check reports

### Departments

On this page you can `Add Department` with certain button. It will open Popup with the form.
When you have data, this page will show `Departments list`.
Each item has several actions:
- View - click on Department name to open it's Data and Employee list;
- Edit - opens Popup with form;
- Remove - removes Department with all the Employees.

### Department view

On this page you can `Add Employee` with certain button. It will open Popup with the form.
When you have data, this page will show `Employee list`.
Each item has several actions:
- Edit - opens Popup with form;
- Remove - removes Employee from the Department.

### Reports

Here you can select one of 2 reports.

## Tests

API controllers has test coverage. To run tests, you need to enter the `hr-app_api_*` container.

From project root folder run command:

```
docker compose exec api bash
```

You should be in the code folder `/var/www/api`

Run command:

```
./vendor/bin/phpunit
```

This will run tests located in `tests` folder.

## Possible issues

1. Sometimes Department list shows error right after running the container. You just need to wait for a while for API container to run properly.
2. Reloading the page on any URL other than root, shows error. Client container uses default Nginx configuration, so it doesn't understand what to do on reload. 

## Further updates

1. Test coverage for Client part
2. Refactor basic CRUD operations to use Eloquent
3. Add Breadcrumbs to the interface
4. Refactor Client container to fix on page reload issue (minor)
5. Upgrade Reports
