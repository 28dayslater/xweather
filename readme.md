This project is ready to use, find all you need for the frontend in the ``public/ressource`` folder, and built your API respecting the question definition.

Basic controllers and models for this questions have been pre-generated.

If you encountered a permission error, just run ``sudo chmod -R 777.`` on the root folder of your project (projects/challenge)

You will be evaluated on **the quality of your code, the result, the logic you implemented , and the functionalities you successfully implemented**.

At first launch, the project will intialized to install the Laravel/Js environnement, it will take **3-5 minutes**. 
When finish a file named ``install.ready``, will appear on the left menu at the root of the directory. 

**Once you see this file (``install.ready``) you will be able to launch your app following these steps :** 

- Go to the top bar and select `Run -> Run`, you should see a terminal window open on the bottom saying with the follwing informations : 

    ``
    Starting Laravel development server: http://0.0.0.0:8000
    PHP 7.4.12 Development Server (http://0.0.0.0:8000) started
    ``
- Go to the top bar and select `Run -> Open Preview `, you should be able to see the Weather API dashboard on the browser (you can also open the window in your own chrome browser and not in the IDE)

**Only your code (files in the challenge directory not included into the .gitignore files) is saved in this environnement, mysql data is wiped out after the test.**

If you are retrying this question because you had some extra time allowed, you must delete the ``install.ready`` file or/and the ``install.lock`` file at the root of the project.

Then you can click on the ``Run->Install`` button on the topbar and wait for the process to finish. This will rebuild the Laravel environnement and setup a server.

**You have root (sudo) access to this environnement.**

**If you do not want to use online IDE, feel free to use your own Laravel dev env, but you need to keep all the files in this repo with the same version and packages. Do not forget to push your code**.