# OfflineProvidence

## Setup

You need to define the following constants in your `setup.php` file :

```
define('__DH_CA_WS_USER__', "your_ws_username_here");
define('__DH_CA_WS_PASS__', "your_ws_password_here");
define('__DH_CA_NO_SSL__', true); // Set to true if you don't have a valid SSL certificate
```

## Deploy

### Install plugin

In the folder containing both pawtucket and providence, clone the repository :

```
git clone https://github.com/ideesculture/offline.git

```

Then, in the providence folder, link the plugin inside the app/plugins folder :

```
cd providence/app/plugins
ln -s ../../../offline/OfflineProvidence OfflineProvidence

```

### Install widget

To install the widget (green/red dot and button to open /offline link) :

```
cd app/widgets
ln -s ../plugins/OfflineProvidence/OfflineProvidenceWidget offline
```

### Deploy offline web app

## Developer instructions

## Working locally with self signed certificates

See : https://stackoverflow.com/questions/23885449/unable-to-resolve-unable-to-get-local-issuer-certificate-using-git-on-windows

```
git config --global http.sslverify false
```


### Build OfflineApp

To develop offlineApp (Vite + Vue app + PWA), you can use within OfflineApp/src
```
vite
```

To build offlineApp, you need to cd inside OfflineApp/src :
```
npm run build
npm run copy
```
