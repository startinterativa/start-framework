if(window.Notification.permission == 'denied') {
    Notification.requestPermission(function(p) {
        if(p == 'denied') {
            alert('Para receber as notificações, é necessário permitir');
        } else {
            var welcome = new Notification("Bem vindo!", {
                    body: "A partir de agora você poderá receber notificações",
                    icon: 'http://startpost.local/View/assets/img/start-post-icone.pnghttp://startpost.local/View/assets/img/start-post-icone.png'
                });
        }
    });
}
