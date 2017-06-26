# TP Link Token / Device Info

Posts data to TP Link cloud API and gathers information about your account / devices to control them outside of the Kasa app. EG: [Tasker](https://play.google.com/store/apps/details?id=net.dinglisch.android.taskerm&hl=en), [IFTTT](https://iftt.com), or any other number of things that can POST data to a URL.

## Installation

Make sure [composer](https://getcomposer.org/download/) is installed and use `php composer.phar install` to install the dependancies. Run a local server of your choice or upload to web server.

Copy `inc.config.sample.php` and rename it to `inc.config.php` and replace the variables with your own.

## Example Requests

### Turning a device on/off

Use the follow JSON inside a POST request using your preferred method to the url listed. EG: `https://use1-wap.tplinkcloud.com?token=YOURTOKENHERE`

```json
{
  "method":"passthrough",
  "params": {
    "deviceId": "DEVICE_ID_HERE",
    "requestData": "{\"system\":{\"set_relay_state\":{\"state\":BOOLEAN_STATE}}}"
  }
}
```
0 will turn a device off, 1 turns it on.

### Getting the state of a device

```json
{
  "method":"passthrough",
  "params": {
    "deviceId": "DEVICE_ID_HERE",
    "requestData": "{\"system\":{\"get_sysinfo\":null},\"emeter\":{\"get_realtime\":null}}"
  }
}
```

Returns a huge list, the info you want is `result->responseData->system->get_sysinfo->relay_state`


#### Credits: [IT Nerd Space](http://itnerd.space/) for the insights into the API.
