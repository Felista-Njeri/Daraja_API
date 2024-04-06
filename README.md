# Daraja_API
How to Implement Daraja API to your website to allow seamless payments using MPESA prompts.

#REQUIREMENTS
1. XAMPP - To run PHP scripts
2. NGROK - To expose your localhost to the internet

#STEPS
1. Initiate project in the htdocs folder inside xampp folder. Run Apache on your PC and open localhost/yourfoldername/filename on your browser.
2. Create account and Download ngrok zip file and extract preferrably in your Local disk (C:\ngrok) Add path as a
    system environment variable. Add your auth token in cmd. Run 'ngrok http 80' in cmd and click on the 
    generated link. It will open XAMPP. In the url ...ngrok-free.app/dashboard/ replace the 'dashboard' with 
    the path to your phpscript. eg ngrok-free.app/foldername/filename
3. In the checkout.php user interfsce, test the stkpush. You can send the ngrok link to other people to test it too. Remember this is a testing
   environment and not a real environment.
4. However, you can configure the code to work in a real environment for a live website by changing the API keys and the URLS. Remember
   that the business has to have a valid paybill number which one can register with safaricom.