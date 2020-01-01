# Inertia React Laravel ui preset 💜
Leverage ReactJS as your view layer within Laravel. This is a
 preset to setup [Inertia](https://inertiajs.com/) and [React](https://reactjs.org/) within a fresh Laravel installation. By using this, you get front-end scaffolding including authentication. Tailwind is setup as well.

The benefit of using Inertia is how you are able to use Laravel and all its features while having a modern front-end along with that modern front-end development workflow;  
```
$ npm run hot
```


## Setup
Follow these 3 steps to execute the scaffolding using this preset.

#### Step 1
Assuming a fresh laravel installation, run the following command to get started;
```
$ composer require --dev ravenberg/laravel-inertia-react-preset
```

#### Step 2
Run the actual command
```
$ php artisan ui:auth inertia-react 
```

#### Step 3
Pull in dependencies and run a build
```
$ npm install && npm run dev
```

🎉 You're all set time to develop your app.