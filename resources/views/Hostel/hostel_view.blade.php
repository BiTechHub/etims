@extends('admin.layouts.master')

@section('main-section')
    <div class="container">

        <div class="page-inner">

            <div class="page-header">

                <h3 class="fw-bold mb-3">Add SiteContent</h3>

                <ul class="breadcrumbs mb-3">

                    <li class="nav-home">

                        <a href="#">

                            <i class="icon-home"></i>

                        </a>

                    </li>

                    <li class="separator">

                        <i class="icon-arrow-right"></i>

                    </li>

                    <li class="nav-item">

                        <a href="#">Manage SiteContent</a>

                    </li>

                    <li class="separator">

                        <i class="icon-arrow-right"></i>

                    </li>

                    <li class="nav-item">

                        <a href="#">Add SiteContent</a>

                    </li>

                </ul>

            </div>

            <div class="row">

                <div class="col-md-12">

                    <div class="card shadow mb-4">

                        <div class="card-header bg-primary text-white">

                            <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Mark Attendance</h5>

                        </div>

                        <div class="card-body">

                            <form>

                                <div class="row g-3">

                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <label for="cal_year"><i class="fas fa-calendar-alt me-2"></i>Calendar
                                                Year</label>

                                            <select class="form-control" id="cal_year" name="cal_year" required>

                                                <option value="">Select Calendar Year</option>

                                                @foreach ($distinctYears as $year)
                                                    <option value="{{ $year }}">{{ $year }}</option>
                                                @endforeach

                                            </select>

                                        </div>

                                    </div>



                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <label for="programme_code" class="form-label">Code</label>

                                            <input type="text" id="programme_code" class="form-control"
                                                placeholder="Enter unique code">

                                        </div>

                                    </div>



                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <label for="programme_id"><i
                                                    class="fas fa-graduation-cap me-2"></i>Programme</label>

                                            <select id="programme_id" class="form-control">

                                                <option value="">Select Programme...</option>

                                                @foreach ($programmes as $prog)
                                                    <option value="{{ $prog->id }}"
                                                        data-year="{{ $prog->financial_year }}">

                                                        {{ $prog->code }} – {{ Str::limit($prog->title, 50) }}

                                                    </option>
                                                @endforeach

                                            </select>

                                        </div>

                                    </div>

                                </div>



                                <hr class="my-4">



                                <div id="datetime-picker" class="datetime-picker">

                                    <div class="d-flex align-items-center flex-wrap gap-3">

                                        <div class="form-group mb-0">

                                            <label><i class="far fa-calendar-alt me-2"></i>Select Date:</label>

                                            <input type="date" id="attend_date" class="form-control">

                                        </div>



                                        <div class="form-group mb-0">

                                            <label><i class="far fa-clock me-2"></i>Select Time:</label>

                                            <input type="time" id="attend_time" class="form-control">

                                        </div>



                                        <div class="form-group mb-0">

                                            <label class="d-block">&nbsp;</label>

                                            <button type="button" class="btn btn-success" id="btn-update-datetime">

                                                <i class="fas fa-save me-1"></i> Update Checkout

                                            </button>

                                        </div>

                                    </div>

                                </div>

                            </form>

                        </div>

                    </div>



                </div>

                <div class="card">

                    <div class="card-body">

                        <div id="loading-indicator" class="loading-indicator">

                            <i class="fas fa-spinner fa-spin"></i>

                            <p>Loading participant data...</p>

                        </div>

                        <div class="table-container table-responsive">

                            <table id="participants-table" class="table">

                                <thead>

                                    <tr>

                                        <th>
                                            <div class="checkbox-wrapper"><input type="checkbox" id="select-all"></div>
                                        </th>


                                        <th>Name</th>
                                        <th>Contact</th>
                                        <th>Designation</th>
                                        <th>Room/Bed</th>
                                        <th>Check In</th>
                                        <th>Check Out</th>


                                    </tr>

                                </thead>

                                <tbody></tbody>

                            </table>

                        </div>



                    </div>



                </div>

            </div>

        </div>

    </div>
@endsection

@section('script')
    <script src="{{ url('/admin') }}/assets/js/plugin/datatables/datatables.min.js"></script>

    <script src="{{ url('/admin') }}/assets/js/kaiadmin.min.js"></script>

    <script src="{{ url('/admin') }}/assets/js/setting-demo2.js"></script>
    <script>
        $(document).ready(function() {

            const yearSelect = $('#cal_year');
            const programmeSelect = $('#programme_id');
            const loading = $('#loading-indicator');
            const tableElem = $('#participants-table');
            const datetimePicker = $('#datetime-picker');

            let selectedParticipants = [];

            // Hide datetime picker initially
            datetimePicker.hide();

            // Initialize DataTable WITHOUT auto loading
            const table = tableElem.DataTable({

                processing: true,
                serverSide: false,
                searching: true,
                ordering: true,
                paging: true,

                ajax: {
                    url: '',
                    dataSrc: '',

                    beforeSend: function() {
                        loading.show();
                    },

                    complete: function() {
                        loading.hide();
                    },

                    error: function(xhr, error, thrown) {

                        console.log(xhr.responseText);

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load participant data'
                        });
                    }
                },

                columns: [

                    // Checkbox
                    {
                        data: null,
                        orderable: false,
                        searchable: false,

                        render: function(data) {

                            return `
                        <div class="checkbox-wrapper">
                            <input type="checkbox"
                                   class="participant-checkbox"
                                   data-id="${data.id}">
                        </div>
                    `;
                        }
                    },

                    // Name
                    {
                        data: 'name',

                        render: function(data, type, row) {

                            return `
                        <div>
                            <strong>${row.title || ''} ${data}</strong>
                            <small class="d-block text-muted">
                                ID: ${row.id}
                            </small>
                        </div>
                    `;
                        }
                    },

                    // Contact
                    {
                        data: null,

                        render: function(data) {

                            return `
                        <div>
                            <div>
                                <i class="fas fa-envelope text-info me-1"></i>
                                ${data.email || 'N/A'}
                            </div>

                            <div>
                                <i class="fas fa-phone text-success me-1"></i>
                                ${data.phone || 'N/A'}
                            </div>
                        </div>
                    `;
                        }
                    },

                    // Designation
                    {
                        data: 'designation',
                        defaultContent: 'N/A'
                    },

                    // Room & Bed
                    {
                        data: null,

                        render: function(data) {

                            let roomNo = 'N/A';
                            let bedNo = 'N/A';

                            if (data.bed) {

                                bedNo = data.bed.bed_number || 'N/A';

                                if (data.bed.room) {

                                    roomNo = data.bed.room.room_number || 'N/A';
                                }
                            }

                            return `
                        <div>
                            <div>
                                <strong>Room:</strong> ${roomNo}
                            </div>

                            <div>
                                <strong>Bed:</strong> ${bedNo}
                            </div>
                        </div>
                    `;
                        }
                    },

                    // Check In
                    {
                        data: 'checked_in_at',

                        render: function(data) {

                            if (!data) {

                                return `
                            <span class="text-muted">
                                Not Checked In
                            </span>
                        `;
                            }

                            return `
                        <div class="text-success">
                            <i class="fas fa-sign-in-alt me-1"></i>
                            ${data}
                        </div>
                    `;
                        }
                    },

                    // Check Out
                    {
                        data: null,

                        render: function(data) {

                            if (!data.checkout_time && !data.date) {

                                return `
                            <span class="text-muted">
                                N/A
                            </span>
                        `;
                            }

                            return `
                        <div class="text-danger">

                            <div>
                                <i class="fas fa-sign-out-alt me-1"></i>
                                ${data.checkout_time || 'N/A'}
                            </div>

                            <small>
                                ${data.date || ''}
                            </small>

                        </div>
                    `;
                        }
                    }

                ],

                language: {

                    emptyTable: `
                <div class="empty-table-message">
                    <i class="fas fa-users-slash fa-2x mb-3"></i>
                    <p>Select year & programme to load participants</p>
                </div>
            `
                },

                createdRow: function(row, data) {

                    $(row).attr('data-id', data.id);
                }
            });

            // Year Change
            yearSelect.on('change', function() {

                const year = $(this).val();

                programmeSelect.find('option[data-year]').each(function() {

                    $(this).toggle($(this).data('year') == year);
                });

                programmeSelect.val('');

                table.clear().draw();
            });

            // Programme Change
            programmeSelect.on('change', function() {

                let programmeId = $(this).val();

                if (programmeId) {

                    table.ajax.url(
                        '{{ route('admin.getCheckInParticipants') }}?programme_id=' + programmeId
                    ).load();

                } else {

                    table.clear().draw();
                }
            });

            // Select All
            $('#select-all').on('change', function() {

                $('.participant-checkbox').prop('checked', this.checked);

                updateSelectedParticipants();

                toggleDateTimePicker();
            });

            // Individual Checkbox
            tableElem.on('change', '.participant-checkbox', function() {

                updateSelectedParticipants();

                $('#select-all').prop(
                    'checked',
                    $('.participant-checkbox').length === $('.participant-checkbox:checked').length
                );

                toggleDateTimePicker();
            });

            // Update Selected
            function updateSelectedParticipants() {

                selectedParticipants = $('.participant-checkbox:checked')
                    .map(function() {

                        return $(this).data('id');

                    }).get();
            }

            // Toggle DateTime Picker
            function toggleDateTimePicker() {

                if (selectedParticipants.length > 0) {

                    datetimePicker.show();

                } else {

                    datetimePicker.hide();
                }
            }

            // Default date
            $('#attend_date').val(
                new Date().toISOString().split('T')[0]
            );

        });
    </script>
   
      <script>

         const loading = $('#loading-indicator');
        $('#btn-update-datetime').on('click', function (e) {

    e.preventDefault();

    let date = $('#attend_date').val();
    let time = $('#attend_time').val();

    // Get checked participants
    let selectedParticipants = [];

    $('.participant-checkbox:checked').each(function () {

        selectedParticipants.push($(this).data('id'));

    });

    // Validation
    if (selectedParticipants.length === 0) {

        alert('Please select at least one participant.');

        return;
    }

    if (!date || !time) {

        alert('Please select both date and time.');

        return;
    }

    loading.show();

    $.ajax({

        url: '{{ route("admin.participants.updateDatetime") }}',

        type: 'POST',

        data: {

            participant_ids: selectedParticipants,
            date: date,
            time: time,
            _token: '{{ csrf_token() }}'
        },

        success: function (response) {

            loading.hide();

            alert('Checkout datetime updated successfully.');

            // Clear checkboxes
            $('.participant-checkbox').prop('checked', false);
            $('#select-all').prop('checked', false);

            datetimePicker.hide();

            // Reload DataTable
            table.ajax.reload(null, false);

            // Clear inputs
            $('#attend_date').val('');
            $('#attend_time').val('');
        },

        error: function (xhr) {

            loading.hide();

            let errorMessage = 'Something went wrong';

            if (xhr.responseJSON && xhr.responseJSON.message) {

                errorMessage = xhr.responseJSON.message;
            }

            alert(errorMessage);

            console.log(xhr.responseText);
        }

    });

});

      </script>



    {{-- 
<script>

$(function () {

  const yearSelect = $('#cal_year');

  const programmeSelect = $('#programme_id');

  const loading = $('#loading-indicator');

  const tableElem = $('#participants-table');

  const datetimePicker = $('#datetime-picker');

  let selectedParticipants = [];






  yearSelect.on('change', () => {

    const year = yearSelect.val();

    programmeSelect.find('option[data-year]').each(function () {

      $(this).toggle($(this).data('year') == year);

    });

    programmeSelect.val('').trigger('change');

    table.clear().draw();

  });



  programmeSelect.on('change', () => {

    if (programmeSelect.val()) {

      table.ajax.reload();

    } else {

      table.clear().draw();

    }

  });



  $('#select-all').on('change', function () {

    $('.participant-checkbox').prop('checked', this.checked);

    updateSelectedParticipants();

    toggleDateTimePicker();

  });



  tableElem.on('change', '.participant-checkbox', function () {

    updateSelectedParticipants();

    $('#select-all').prop('checked', $('.participant-checkbox').length === $('.participant-checkbox:checked').length);

    toggleDateTimePicker();

  });



  function updateSelectedParticipants() {

    selectedParticipants = $('.participant-checkbox:checked').map(function () {

      return $(this).data('id');

    }).get();

  }



  function toggleDateTimePicker() {

    datetimePicker.toggle(selectedParticipants.length > 0);

  }



  function performAction(url, data, message) {

    if (!selectedParticipants.length) {

      Swal.fire({

        icon: 'warning',

        title: 'No Selection',

        text: 'Please select at least one participant.'

      });

      return;

    }



    loading.show();

    $.post(url, { ...data, participant_ids: selectedParticipants, _token: '{{ csrf_token() }}' })

      .done(() => {

        alert('success');
        table.ajax.reload();

        selectedParticipants = [];

        $('.participant-checkbox, #select-all').prop('checked', false);

        datetimePicker.hide();

      })

      .fail(xhr => {

        Swal.fire({

          icon: 'error',

          title: 'Error',

          text: 'Operation failed: ' + xhr.responseText

        });

      })

      .always(() => loading.hide());

  }



  // Attendance actions

  $('#btn-mark').on('click', function() {

    performAction('{{ route("admin.hostel.updateAttendence") }}', 

      { status: 'Present' }, 

      'Attendance marked as Present for selected participants.'

    );

  });



  $('#btn-clear').on('click', function() {

    performAction('{{ route("admin.hostel.updateAttendence") }}', 

      { status: 'Absent' }, 

      'Attendance marked as Absent for selected participants.'

    );

  });



  $('#btn-email').on('click', function() {

    performAction('{{ route("admin.hostel.updateAttendence") }}', 

      { status: 'mark by email' }, 

      'Confirmation emails will be sent to selected participants.'

    );

  });



  $('#btn-email-r').on('click', function() {

    performAction('{{ route("admin.hostel.updateAttendence") }}', 

      { status: 'regret by email' }, 

      'Regret emails will be sent to selected participants.'

    );

  });



  // Datetime picker

   $('#btn-update-datetime').on('click', function(e) {

    e.preventDefault(); // Prevent default form submission behavior

    

    const date = $('#attend_date').val();

    const time = $('#attend_time').val();



    if (!selectedParticipants.length) {

      Swal.fire({

        icon: 'warning',

        title: 'No Selection',

        text: 'Please select at least one participant.'

      });

      return;

    }



    if (!date || !time) {

      Swal.fire({

        icon: 'warning',

        title: 'Incomplete Data',

        text: 'Please select both date and time.'

      });

      return;

    }



    loading.show();

    

    $.ajax({

      url: '{{ route("admin.participants.updateDatetime") }}',

      method: 'POST',

      data: { 

        participant_ids: selectedParticipants, 

        date: date, 

        time: time,

        _token: '{{ csrf_token() }}' 

      },

      success: function(response) {

        loading.hide();

        Swal.fire({

          icon: 'success',

          title: 'Success',

          text: 'Checkout datetime updated for selected participants.',

          timer: 2000,

          showConfirmButton: false

        });

        

        // Clear selections

        selectedParticipants = [];

        $('.participant-checkbox, #select-all').prop('checked', false);

        datetimePicker.hide();

        

        // Reload table data without refreshing the page

        table.ajax.reload(null, false); // false means don't reset paging

      },

      error: function(xhr) {

        loading.hide();

        Swal.fire({

          icon: 'error',

          title: 'Error',

          text: 'Operation failed: ' + (xhr.responseJSON?.message || xhr.statusText)

        });

      }

    });



    // Clear the inputs

    $('#attend_date').val('');

    $('#attend_time').val('');

  });



  // Initialize date picker with today's date

  $('#attend_date').val(new Date().toISOString().split('T')[0]);

});

</script> --}}





    <script>
        document.getElementById('programme_code').addEventListener('keyup', function() {

            let code = this.value.trim();

            let year = document.getElementById('cal_year').value;

            let select = document.getElementById('programme_id');



            if (code !== '' && year !== '') {

                fetch('/get-programme-by-code', {

                        method: 'POST',

                        headers: {

                            'Content-Type': 'application/json',

                            'X-CSRF-TOKEN': '{{ csrf_token() }}'

                        },

                        body: JSON.stringify({
                            code: code,
                            year: year
                        })

                    })

                    .then(response => response.json())

                    .then(data => {

                        if (data && data.id) {

                            select.innerHTML =
                                `<option value="${data.id}" data-code="${data.unique_id}" selected>${data.title}</option>`;

                            select.disabled = false;

                            select.dispatchEvent(new Event('change'));

                        } else {

                            select.innerHTML = '<option value="">-- Select Programme --</option>';

                            select.disabled = true;



                            Swal.fire({

                                icon: 'warning',

                                title: 'Not Found',

                                text: 'No programme found for this code and financial year.'

                            });

                        }

                    })

                    .catch(error => {

                        console.error('Error:', error);

                        select.innerHTML = '<option value="">-- Select Programme --</option>';

                        select.disabled = true;



                        Swal.fire({

                            icon: 'error',

                            title: 'Error',

                            text: 'Failed to fetch programme.'

                        });

                    });

            } else {

                select.innerHTML = '<option value="">-- Select Programme --</option>';

                select.disabled = true;

            }

        });



        // 👉 Auto-fill the programme code when selected from dropdown
    </script>

    <script>
        document.getElementById('programme').addEventListener('change', function() {

            let programmeId = this.value;



            if (programmeId) {

                fetch('/get-programme-unique-id', {

                        method: 'POST',

                        headers: {

                            'Content-Type': 'application/json',

                            'X-CSRF-TOKEN': '{{ csrf_token() }}'

                        },

                        body: JSON.stringify({
                            id: programmeId
                        })

                    })

                    .then(response => response.json())

                    .then(data => {

                        if (data && data.code) {

                            document.getElementById('programme_code').value = data.code;

                        } else {

                            document.getElementById('programme_code').value = '';

                            Swal.fire({

                                icon: 'warning',

                                title: 'Not Found',

                                text: 'Unique ID not found for the selected programme.'

                            });

                        }

                    })

                    .catch(error => {

                        console.error('Error:', error);

                        Swal.fire({

                            icon: 'error',

                            title: 'Error',

                            text: 'Failed to fetch unique ID.'

                        });

                    });

            }

        });
    </script>





    <script>
        document.getElementById('programme_id').addEventListener('change', function() {

            let programmeId = this.value;



            if (programmeId) {

                fetch('/get-programme-unique-id', {

                        method: 'POST',

                        headers: {

                            'Content-Type': 'application/json',

                            'X-CSRF-TOKEN': '{{ csrf_token() }}'

                        },

                        body: JSON.stringify({
                            id: programmeId
                        })

                    })

                    .then(response => response.json())

                    .then(data => {

                        if (data && data.code) {

                            document.getElementById('programme_code').value = data.code;

                        } else {

                            document.getElementById('programme_code').value = '';

                            Swal.fire({

                                icon: 'warning',

                                title: 'Not Found',

                                text: 'Unique ID not found for the selected programme.'

                            });

                        }

                    })

                    .catch(error => {

                        console.error('Error:', error);

                        Swal.fire({

                            icon: 'error',

                            title: 'Error',

                            text: 'Failed to fetch unique ID.'

                        });

                    });

            }

        });
    </script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const calYearSelect = document.getElementById('cal_year');

            const programmeSelect = document.getElementById('programme_id');

            const allProgrammeOptions = Array.from(programmeSelect.options).slice(1); // Skip placeholder



            // Initially disable programme dropdown

            programmeSelect.disabled = true;



            calYearSelect.addEventListener('change', function() {

                const selectedYear = this.value;



                // Reset options

                programmeSelect.innerHTML = '<option value="">Select Programme...</option>';



                if (selectedYear) {

                    const matchingOptions = allProgrammeOptions.filter(option =>

                        option.getAttribute('data-year') === selectedYear

                    );



                    matchingOptions.forEach(option => {

                        programmeSelect.appendChild(option);

                    });



                    programmeSelect.disabled = false;

                } else {

                    programmeSelect.disabled = true;

                }

            });

        });
    </script>
@endsection
