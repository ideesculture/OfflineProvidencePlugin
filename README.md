# OfflineProvidence

## Deploy

### Install plugin

### Install widget

To install the widget (green/red dot and button to open /offline link) :

```
cd app/widgets
ln -s ../plugins/OfflineProvidence/OfflineProvidenceWidget offline
```

### Deploy offline web app

## Developer instructions

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