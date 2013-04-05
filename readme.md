# Installation

Add the following repository to your composers require
```
"mcn/email": "dev-master"
```

In your application you must add an alias for doctrine.objectmanager that points to the object/entity manager you are using.


### Logging / notifications
If you wish to log for missing templates to email admins or simular you need to add alias for ```mcn.loge``` to a Zend\Log\LoggerInterface
