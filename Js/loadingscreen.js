function showSpinningLoadingScreen() {
    // Create the loading screen container
    const loadingScreen = document.createElement('div');
    loadingScreen.id = 'loadingScreen';
    loadingScreen.style.position = 'fixed';
    loadingScreen.style.top = '0';
    loadingScreen.style.left = '0';
    loadingScreen.style.width = '100%';
    loadingScreen.style.height = '100%';
    loadingScreen.style.backgroundColor = 'white'; // Semi-transparent black
    loadingScreen.style.zIndex = '1000'; // Make sure it's on top
    loadingScreen.style.display = 'flex'; // Flexbox for centering
    loadingScreen.style.justifyContent = 'center'; // Center horizontally
    loadingScreen.style.alignItems = 'center'; // Center vertically

    // Add the spinning image
    const img = document.createElement('img');
    img.src = '../pic/logonatinnobg.png'; // Replace with your image path
    img.alt = 'Loading...';
    img.style.width = '150px'; // Adjust size of the image
    img.style.height = 'auto';
    img.style.animation = 'spin 2s linear infinite'; // Spinning animation

    // Add the image to the loading screen
    loadingScreen.appendChild(img);

    // Create and inject CSS for the spinning animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    `;
    document.head.appendChild(style);

    // Append the loading screen to the body
    document.body.appendChild(loadingScreen);

    // Simulate loading and hide the loading screen after a delay
    setTimeout(() => {
        document.body.removeChild(loadingScreen); // Remove the loading screen
    }, 3000); // Adjust this duration (3000 ms = 3 seconds)
}

// Call the function to show the loading screen
showSpinningLoadingScreen();
