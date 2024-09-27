document.addEventListener('DOMContentLoaded', function() {
    function onWindowResize() {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
      
        renderer.setSize(window.innerWidth, window.innerHeight);
      }
      
      // Ajoutez l'événement lors du redimensionnement de la fenêtre
      window.addEventListener('resize', onWindowResize, false);
     
      function adjustCameraDistance() {
        camera.position.z = 5 * (window.innerWidth / 1920); // Ajuste la distance en fonction de la largeur de l'écran de référence (1920px pour 1080p)
      }
      
    // Initialisation de la scène
    var scene = new THREE.Scene();
  
    // Initialisation de la caméra
    var camera = new THREE.PerspectiveCamera(75, 800 / 600, 0.1, 1000);
    camera.position.z = 5;
  
    // Initialisation du rendu
    var renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    renderer.setSize(window.innerWidth, window.innerHeight); // S'adapte à la taille de la fenêtre
    document.getElementById('container').appendChild(renderer.domElement);
    
    // Assurez-vous que la caméra est bien placée
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    
    // Ajouter un listener pour ajuster la taille lors du redimensionnement de la fenêtre
    window.addEventListener('resize', function() {
      renderer.setSize(window.innerWidth, window.innerHeight);
      camera.aspect = window.innerWidth / window.innerHeight;
      camera.updateProjectionMatrix();
    });
    
  
    // S'assurer que l'élément container est trouvé après le chargement du DOM
    var container = document.getElementById('container');
    container.appendChild(renderer.domElement);
  
    // Lumières et modèle glTF (suite de votre script)
 
  
    var directionalLight = new THREE.DirectionalLight(0xffffff, 2);
    directionalLight.position.set(1, 1, 1);
    scene.add(directionalLight);
    
    var ambientLight = new THREE.AmbientLight(0x404040, 2); // Lumière douce
    scene.add(ambientLight);
    
var loader = new THREE.GLTFLoader();
var model;

loader.load(
  './liaisons/model3D/lanote.gltf',
  function (gltf) {
    model = gltf.scene;
    console.log(model);  // Vérifiez si le modèle est bien chargé dans la console

    // Ajuster l'échelle du modèle
    model.scale.set(2.5, 2.5, 2.5); // Réduire encore l'échelle si nécessaire
    camera.position.set(0, 2, 6); // Déplacer la caméra plus haut sur l'axe Y (2 unités) et plus loin sur l'axe Z (5 unités)


    
    // Ajouter le modèle à la scène
    scene.add(model);
    animate();
  },
  undefined,
  function (error) {
    console.error('Une erreur est survenue lors du chargement du modèle :', error);
  }
);

function animate() {
    requestAnimationFrame(animate);
  
    // Optionnel : Faire tourner le modèle
    if (model) {
        model.rotation.x = Math.PI / 16; // Inclinaison de 45° sur l'axe X
        model.rotation.z = Math.PI / 16; // Inclinaison de 45° sur l'axe Z
        model.rotation.y += 0.003; // Ajustez la vitesse de rotation
        // Optionnel : Faire un léger mouvement de flottement sur l'axe Y
        model.position.y = Math.sin(Date.now() * 0.001) * 0.15; 
    }
  
   

    renderer.render(scene, camera);
  }
});
