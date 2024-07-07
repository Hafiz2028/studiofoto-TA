<div id="catalogContainer" class="catalog-container">
    <div class="catalog-content-wrapper">
        <div class="catalog-header">
            <div class="catalog-logo">
                <img id="venueLogo" src="" alt="Logo">
            </div>
            <h5 id="venueName"></h5>
            <span id="catalogClose" class="catalog-close">&times;</span>
        </div>
        <div class="catalog-content">
            <div id="priceCatalogContent"></div>
        </div>
    </div>
</div>
<style>
    .catalog-container {
        display: flex;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        overflow-y: auto;
        font-family: 'Arial', sans-serif;
        color: #333;
        align-items: center;
        justify-content: center;
        display: none;
    }

    .catalog-content-wrapper {
        background-color: #ffffff;
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        padding: 30px;
        width: 80%;
        max-width: 900px;
        max-height: 80vh;
        overflow-y: auto;
    }

    .catalog-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }

    .catalog-header h5 {
        margin: 0;
        font-size: 1.8rem;
        font-weight: bold;
        color: #333;
    }

    .catalog-close {
        cursor: pointer;
        font-size: 24px;
        color: #999;
        transition: color 0.3s ease;
    }

    .catalog-close:hover {
        color: #666;
    }

    .catalog-content {
        line-height: 1.6;
        padding-top: 5px;
    }

    .catalog-content h3 {
        margin-top: 0;
        font-size: 2rem;
        font-weight: bold;
        color: #333;
        padding-left: 5px;
    }

    .catalog-content h4 {
        margin-top: 15px;
        font-size: 1.6rem;
        font-weight: bold;
        color: #333;
        padding-left: 10px;
    }

    .catalog-content ul {
        list-style-type: none;
        padding-left: 20px;
        margin-top: 10px;
    }

    .catalog-content ul li {
        margin-bottom: 8px;
    }

    .catalog-logo {
        margin-right: 20px;
    }

    .catalog-logo img {
        max-height: 80px;
        margin-bottom: 20px;
    }

    .text-right {
        text-align: right;
    }

    /* Style for horizontal layout */
    .catalog-content ul.row {
        display: flex;
        flex-wrap: wrap;
    }

    .catalog-content ul.row li {
        flex: 1 1 45%;
        /* Adjust width as needed */
        margin-right: 10px;
    }
</style>
<script>
    $(document).ready(function() {
        $('#katalogBtn').on('click', function() {
            let venueId = '{{ $venue->id }}';
            $.ajax({
                url: '/api/price-catalog/' + venueId,
                method: 'GET',
                success: function(data) {
                    console.log('Data yang diterima dari API:', data);

                    $('#venueName').text(data.venue);
                    $('#venueLogo').attr('src', data.logo);

                    let content = '';
                    data.service_events.forEach(serviceEvent => {
                        content += `<h4>${serviceEvent.name}</h4>`;
                        serviceEvent.service_packages.forEach(package => {
                            content +=
                                `<div><strong>${package.name}</strong></div>`;

                            // Add-On Packages
                            if (package.addOnPackageDetails.length > 0) {
                                content +=
                                    `<div>Add-On Packages:</div><ul class="row">`;
                                package.addOnPackageDetails.forEach(
                                    addOn => {
                                        content +=
                                            `<li><span class="badge badge-info">${addOn.sum} ${addOn.name}</span></li>`;
                                    });
                                content += `</ul>`;
                            }

                            // Print Photos
                            if (package.printPhotoDetails.length > 0) {
                                content +=
                                    `<div>Print Photos:</div><ul class="row">`;
                                package.printPhotoDetails.forEach(
                                    printPhotoDetail => {
                                        content +=
                                            `<li class="col-md-6"><span class="badge badge-info">Size ${printPhotoDetail.size}</span></li>`;
                                    });
                                content += `</ul>`;
                            }

                            // Frame Photos
                            if (package.framePhotoDetails.length > 0) {
                                content +=
                                    `<div>Frame Photos:</div><ul class="row">`;
                                package.framePhotoDetails.forEach(
                                    framePhotoDetail => {
                                        content +=
                                            `<li class="col-md-6"><span class="badge badge-info">Size ${framePhotoDetail.size}</span></li>`;
                                    });
                                content += `</ul>`;
                            }

                            // Service Package Details
                            content += `<div>Deskripsi Detail:</div><ul>`;
                            package.details.forEach(detail => {
                                let timeText = '';
                                switch (detail.time) {
                                    case 0:
                                        timeText = '30 Menit';
                                        break;
                                    case 1:
                                        timeText = '60 Menit';
                                        break;
                                    case 2:
                                        timeText = '90 Menit';
                                        break;
                                    case 3:
                                        timeText = '120 Menit';
                                        break;
                                    default:
                                        timeText =
                                            'Durasi tidak diketahui';
                                        break;
                                }
                                content +=
                                    `<li>${detail.description} <span class="badge badge-info">${timeText}</span>: Rp ${detail.price}</li>`;
                            });
                            content += `</ul>`;
                        });
                    });

                    $('#priceCatalogContent').html(content);
                    $('#catalogContainer').css('opacity', 0).css('display', 'flex')
                        .animate({
                            opacity: 1
                        }, 400);
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        });

        $('#catalogClose').on('click', function() {
            $('#catalogContainer').fadeOut();
        });

        $(window).on('click', function(event) {
            if ($(event.target).is('#catalogContainer')) {
                $('#catalogContainer').fadeOut();
            }
        });

        $('.catalog-content-wrapper').on('click', function(event) {
            event.stopPropagation();
        });
    });
</script>
