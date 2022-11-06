<p align="center">
<img  src="./fruitsBytes-MonCash-Postman.png" alt="FruitsBytes-Moncash-PHP">
</p>

`Version 1.0.0`

# Postman

This is a Postman API similar to the [the official one](https://documenter.getpostman.com/view/1199944/UVeJKju3)
provided in
the portal documentation, with additional features:

### ✨ Merchant + Client endpoints
Both end points and their requests are handled.

### ✨ Environment

Set the environment variables to easily switch between `live` and `sandbox`, or securely share your business API
endpoints with co-developers.

### ✨ Automatic Authentication Bearer

Every request that need will automatically fetch a new authentication token and re-use it it is not expired yet.

### ✨ Share state between requests
Some values are automatically saved to be shared for the next API call in the payment flow.

> Example:
> 
> the `Create Payment` call from the client endpoints automatically  reserves the `payment_token.token` for the `Payment/Redirect` request.
> If you leave the `token` parameter empty it will use the last saved token.

### ✨ RSA base64 encryption 

Enjoy!

## How to use

1) Download the file [MonCash_API_Collection_v1.0.0.json](./MonCash_API_Collection_v1.0.0.json).
2) Click Import

![Step1-3](./assets/images/steps1-3.png)

3) Click Choose Files and specify `MonCash_API_Collection_v1.0.0.json`.

![Step1-3](./assets/images/steps-4.png)

4) Click the eye icon (![eye]()) to setup an Environment variables ( learn
   more [here](https://medium.com/apis-with-valentine/demystifying-postman-variables-how-and-when-to-use-different-variable-scopes-66ad8dc11200)
   and [here](https://learning.postman.com/docs/sending-requests/variables/) ).
4) Click Add.
5) Enter an Environment name.
6) Copy your API Keys.
1) Enter a Key and a Value.

### More on how to manage Postman collection

- Official
  documentation: [Importing and exporting data](https://learning.postman.com/docs/getting-started/importing-and-exporting-data/)
    - [Importing from GitHub repositories](https://learning.postman.com/docs/getting-started/importing-and-exporting-data/#importing-from-github-repositories)
- Videos:

| Title                                         | Link                                                                                                                                      |
|-----------------------------------------------|-------------------------------------------------------------------------------------------------------------------------------------------|
| How to use and share Postman Collections      | [![Video Importing and exporting collections](https://img.youtube.com/vi/bF8q8wvLs8A/1.jpg)](https://www.youtube.com/watch?v=bF8q8wvLs8A) |
| How to Share Postman Collections              | [![Video Importing and exporting collections](https://img.youtube.com/vi/b9VlFDlwKvI/1.jpg)](https://www.youtube.com/watch?v=b9VlFDlwKvI) |
| Postman How to Connect to Your Git Repository | [![Video Importing and exporting collections](https://img.youtube.com/vi/8jJHXLVYOh0/1.jpg)](https://www.youtube.com/watch?v=8jJHXLVYOh0) |
| Generate a Collection From a Specification    | [![Video Importing and exporting collections](https://img.youtube.com/vi/gljWt9tDKOY/1.jpg)](https://www.youtube.com/watch?v=gljWt9tDKOY) |    
       
     

