<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Map $model */

$id = $_GET['campaignId'];
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Maps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<style type="text/css">
    html, body { height: 100%; margin: 0; padding: 0; }
    #map { width: 1660px; height: 1000px; }
</style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<style>
    body {
        background-color: #000;
    }
    #map {
        border: 5px solid #000;
        z-index: 100;
        position: fixed;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }
</style>

    <div id="map"></div>

<script type="text/javascript">
        var map = L.map('map', {
            crs: L.CRS.Simple,
            center: [500, 880],
            minZoom: <?= $model->minzoom; ?>,
            maxZoom: <?= $model->maxzoom; ?>,
            zoom: <?= $model->defaultzoom; ?>
        });
        var bounds = [[0,0], [1000,1660]];
        var image = L.imageOverlay('<?= $model->image; ?>', bounds).addTo(map);
        map.setMaxBounds(bounds);
        map.on('drag', function() {
            map.panInsideBounds(bounds, { animate: false });
        });
        var greenIcon = L.icon({
            iconUrl: 'leaf-green.png',
            iconSize:     [38, 95], // size of the icon
            iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
            shadowAnchor: [4, 62],  // the same for the shadow
            popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
        });
        var LeafIcon = L.Icon.extend({
            options: {
                iconSize:     [38, 95],
                iconAnchor:   [22, 94],
                shadowAnchor: [4, 62],
                popupAnchor:  [-3, -76]
            }
        });
        var greenIcon = new LeafIcon({iconUrl: '/maps-marker-green.png'}),
        redIcon = new LeafIcon({iconUrl: '/maps-marker-red.png'}),
        blueIcon = new LeafIcon({iconUrl: '/maps-marker-blue.png'});
        yellowIcon = new LeafIcon({iconUrl: '/maps-marker-yellow.png'});
        purpleIcon = new LeafIcon({iconUrl: '/maps-marker-purple.png'});
        L.icon = function (options) {
            return new L.Icon(options);
        };
        map.on('mousedown', dropTestPin);
        function dropTestPin(e)
        {
            if (e.originalEvent.ctrlKey) {
                map.dragging.disable();
                console.log(e.latlng);
                L.marker(e.latlng, {icon: redIcon}).addTo(map).bindPopup(e.latlng.toString());
            }
        }

        // backend map markers start
        <?php foreach ($markers as $m): ?>
        <?php if (!empty($m->deleted)): ?>
            <?php continue; ?>
        <?php endif; ?>
        L.marker([<?= $m->lat; ?>, <?= $m->lng; ?>], {icon: <?= $m->color; ?>Icon}).addTo(map).bindPopup("<?= $m->name; ?>");
        <?php endforeach; ?>// backend map markers finish
    </script>


