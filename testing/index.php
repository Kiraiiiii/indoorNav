<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Markerless Indoor AR Navigation</title>
    <script src="https://aframe.io/releases/1.2.0/aframe.min.js"></script>
    <script src="https://cdn.rawgit.com/jeromeetienne/AR.js/2.0.8/aframe/build/aframe-ar.js"></script>
    <script src="https://cdn.rawgit.com/donmccurdy/aframe-extras/v6.0.0/dist/aframe-extras.min.js"></script>
    <script>
      AFRAME.registerComponent('navigation', {
        init: function() {
          var arrow = document.querySelector('#arrow');
          var destination = document.querySelector('#destination');
          var instructionSound = document.querySelector('#instruction-sound');
          var lastPosition = new THREE.Vector3();

          arrow.addEventListener('componentchanged', function(event) {
            if (event.detail.name === 'position') {
              var direction = new THREE.Vector3();
              var currentPosition = arrow.object3D.getWorldPosition(direction);
              var distance = lastPosition.distanceTo(currentPosition);

              if (distance > 1) {
                var lookAtPosition = new THREE.Vector3();
                arrow.object3D.getWorldDirection(direction).multiplyScalar(5).add(currentPosition);
                destination.object3D.getWorldPosition(lookAtPosition);

                if (lookAtPosition.x > currentPosition.x) {
                  instructionSound.src = 'turn-left.mp3';
                } else if (lookAtPosition.x < currentPosition.x) {
                  instructionSound.src = 'turn-right.mp3';
                }

                instructionSound.play();
                lastPosition.copy(currentPosition);
              }
            }
          });
        }
      });
    </script>
  </head>
  <body>
    <a-scene vr-mode-ui="enabled: false" embedded arjs="debugUIEnabled: false;">
      <a-assets>
        <audio id="instruction-sound"></audio>
        <a-asset-item id="arrow" src="./assests/glTF/scene.gltf"></a-asset-item>
      </a-assets>
      <a-entity gps-camera rotation-reader></a-entity>
      <a-entity id="arrow" position="0 0 -2" gltf-model="#arrow" navigation></a-entity>
      <a-entity id="destination" geometry="primitive: sphere; radius: 0.1" material="color: red" position="0 0 -5"></a-entity>
    </a-scene>
  </body>
</html>


