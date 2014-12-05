#Domain Ping API

##Usage

###Auth
Add a header variable 'authkey' with the value of your access key

####jQuery Example
```javascript
$.ajax({
    url: '/ping.php?domains[]=google.com&domains[]=example.com',
    headers: {
        'authkey':'Your authkey string here...',
        'Content-Type':'application/json'
    },
    method: 'GET',
    dataType: 'json',
    data: YourData,
    success: function(data){
        console.log('Succes: ' + data);
    }
});
```

####Response
{"google.com": {"latency": 5},"example.com": {"latency": 79}}
