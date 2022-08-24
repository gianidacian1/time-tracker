# Time-tracker

A simple time-tracker to track your tasks.

Create account and add tasks.
We display only the tasks for the logged user.
You can see the total time of each task, how much you've worked that day, and the history of every task.
When you're done with the task, just mark it as done.






# Installation

git clone repository and then run "cd time-tracker/"

run "composer install"

create db for example named "time-tracker"

create ".env" file from ".env.example"

add your mysql data to connect to the db

run "php artisan migrate"

If you run into trouble with the above command, check:

https://www.codegrepper.com/code-examples/php/Schema%3A%3AdefaultStringLength%28191%29

run "php artisan serve"

# Improvements in the future.

At the moment we add a task just by typing it, we can add more feature to it as:
    <ul>
        <li>Adding CRUD for projects, and asociating tasks with projects</li>
        <li>Creating user roles, and the admin to have the ability to assign tasks to users</li>
        <li>Multiple statuses as: To Do, In Progress, Ready for Testing, Move live etc</li>
        <li>Add posibility to have a deadline, in a time interval</li>
        <li>Add posibility to upload files to the task</li>
        <li>Add comments to the task</li>
        <li>Integrating it with various api's as toggle or even jira</li>
    </ul>
I will work at these in the future, if you have any future ideeas, feel free to send me a message.
