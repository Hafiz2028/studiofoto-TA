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
        /* padding-left: 10px; */
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
    .package-row-container {
    display: flex;
    gap: 20px; /* Jarak antara kolom kiri dan kanan */
    margin-bottom: 20px; /* Jarak antara setiap baris package */
    }

    .left-column {
        flex: 1; /* Kolom kiri mengambil ruang yang sesuai */
    }

    .right-column {
        flex: 2; /* Kolom kanan juga mengambil ruang yang sama */
    }

    .package-label {
        font-style:italic;
        font-weight: bold;
        margin-bottom: 5px;
        margin-left: 5px;
    }

    .package-detail {
        margin-bottom: 10px;
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
                        content += `<h3 class ="pl-0">${serviceEvent.name}</h3>`;
                        serviceEvent.service_packages.forEach(package => {
                            content +=
                                `<div class="package-name"><h4>${package.name}</h4></div>`;

                                content += `
                                <div class="package-row-container d-flex">
                                    <!-- Kolom Kiri: Add-On Packages, Print Photos, Frame Photos -->
                                    <div class="left-column">
                                        <!-- Add-On Packages -->
                                        ${package.addOnPackageDetails.length > 0 ? `
                                        <div class="package-row">
                                            <div class="package-label">Add-On Packages :</div>
                                            <div class="package-detail">
                                                ${package.addOnPackageDetails.map(addOn => `
                                                    <span class="badge badge-info ml-1">${addOn.sum} ${addOn.name}</span>
                                                `).join('')}
                                            </div>
                                        </div>` : ''}

                                        <!-- Print Photos -->
                                        ${package.printPhotoDetails.length > 0 ? `
                                        <div class="package-row">
                                            <div class="package-label">Print Photos :</div>
                                            <div class="package-detail">
                                                ${package.printPhotoDetails.map(printPhotoDetail => `
                                                    <span class="badge badge-info ml-1">Size ${printPhotoDetail.size}</span>
                                                `).join('')}
                                            </div>
                                        </div>` : ''}

                                        <!-- Frame Photos -->
                                        ${package.framePhotoDetails.length > 0 ? `
                                        <div class="package-row">
                                            <div class="package-label">Frame Photos :</div>
                                            <div class="package-detail">
                                                ${package.framePhotoDetails.map(framePhotoDetail => `
                                                    <span class="badge badge-info ml-1">Size ${framePhotoDetail.size}</span>
                                                `).join('')}
                                            </div>
                                        </div>` : ''}
                                    </div>

                                    <!-- Kolom Kanan: Deskripsi Detail -->
                                    <div class="right-column">
                                        <div class="package-label">Rincian Paket Foto:</div>
                                        <ul class="pl-0 pt-0 mt-0">
                                            ${package.details.map(detail => {
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
                                                        timeText = 'Durasi tidak diketahui';
                                                        break;
                                                }
                                                return `<li><span class="badge badge-info">${detail.description}</span> waktu foto <span class="badge badge-info"><span>&plusmn;</span> ${timeText}</span> Harga <span class="badge badge-info">Rp ${detail.price}</span></li>`;
                                            }).join('')}
                                        </ul>
                                        <div class="package-label mt-2">Deskripsi Tambahan:</div>
                                        <div class="pl-4"></div>${package.information}
                                    </div>
                                </div>`;
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
