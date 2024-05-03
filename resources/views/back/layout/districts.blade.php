<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Districts</title>
</head>

<body>
    <form id="districtForm" action="{{ route('submit') }}" method="post">
        @csrf
        <select id="districtSelect" name="district_id">
            <option value="">Pilih Kecamatan</option>
            @foreach ($districts as $district)
                <option value="{{ $district['id'] }}">{{ $district['name'] }}</option>
            @endforeach
        </select>
        <select id="villageSelect" name="village_id" style="display: none;">
            <option value="">Pilih Kelurahan</option>
        </select>
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const districtSelect = document.getElementById('districtSelect');
            const villageSelect = document.getElementById('villageSelect');

            districtSelect.addEventListener('change', function() {
                const selectedDistrictId = this.value;
                if (selectedDistrictId) {
                    villageSelect.style.display = 'inline';
                    villageSelect.innerHTML = '<option value="">Loading...</option>';
                    fetchVillages(selectedDistrictId);
                } else {
                    villageSelect.style.display = 'none';
                    villageSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
                }
            });

            function fetchVillages(districtId) {
                fetch(`https://apiwilayah.metrosoftware.id/api-wilayah-indonesia/api/villages/${districtId}.json`)
                    .then(response => response.json())
                    .then(data => {
                        villageSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
                        data.forEach(village => {
                            const option = document.createElement('option');
                            option.value = village.id;
                            option.textContent = village.name;
                            villageSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching villages:', error);
                        villageSelect.innerHTML = '<option value="">Error fetching data</option>';
                    });
            }
        });
    </script>
</body>

</html>
