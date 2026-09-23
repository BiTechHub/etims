@extends('admin.layouts.master')
@section('main-section')

<link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.css') }}">
<div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Set Paper</h3>
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
          <a href="#">Question Management</a>
        </li>
        <li class="separator">
          <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
          <a href="#">Set Paper</a>
        </li>
      </ul>
    </div>
    <div class="row">
  @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
      <div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="department_select" class="form-label">Department</label>
                    <select name="department_id" id="department_select" class="form-control">
                        <option value="">Select Department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="programme_select" class="form-label">Previous Paper</label>
                    <select name="programme_id" id="programme_select" class="form-control">
                        <option value="">Select Programme</option>
                        <!-- Programmes will be loaded dynamically via AJAX -->
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="programmes_select" class="form-label">Programme</label>
                    <select name="programmes_id" id="programmes_select" class="form-control">
                        <option value="">Select Programme</option>
                        @foreach($programmes as $programme)
                            <option value="{{ $programme->id }}">{{ $programme->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12 mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0"><i class="fas fa-cogs me-2"></i>Selection Mode</h6>
        </div>
        <div class="card-body">
            <div class="text-center mb-3">
                <div class="btn-group" role="group" aria-label="Selection Mode">
                    <button type="button" class="btn btn-outline-primary active" id="manualSelectionBtn">
                        <i class="fas fa-hand-pointer me-1"></i> Manual Selection
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="randomSelectionBtn">
                        <i class="fas fa-random me-1"></i> Random Selection
                    </button>
                </div>
            </div>

            <div class="question-count-input" id="randomCountContainer">
                <label for="question_count" class="form-label">Number of Questions to Select:</label>
                <input type="number" id="question_count" class="form-control" min="1" placeholder="Enter number of questions">
                <small class="text-muted">Total available questions: <span id="totalQuestions">0</span></small>
            </div>
              <form id="selectedQuestionsForm" method="POST" action="{{ route('process.questions') }}">
            @csrf
            <input type="hidden" name="department_id" id="hidden_department_id">
            <input type="hidden" name="programme_id" id="hidden_programme_id">
            <input type="hidden" name="programmes_id" id="hidden_programmes_id">
            <input type="hidden" name="selected_questions" id="selectedQuestionsInput">
            <input type="hidden" name="selection_mode" id="selectionModeInput" value="manual">
            <button type="button" class="btn btn-primary mt-3" id="reviewSelectionBtn">Proceed with Selection</button>
        </form>
        </div>
    </div>
</div>




      <div class="card">
     <div class="card-body">
<div class="table-container table-responsive">
     <table class="table table-bordered table-hover" id="department-data-table">
        <thead>
            <tr>
                <th class="checkbox-cell">
                    <input type="checkbox" id="selectAll" class="checkbox-input">
                </th>
                <th>ID</th>
                <th>Question</th>
                <th>Options</th>
            </tr>
        </thead>
        <tbody>
            <!-- Questions will be loaded dynamically via AJAX -->
        </tbody>
    </table>
</div>

     </div>

      </div>

    <div id="selectionModal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <h3>Selected Questions Review</h3>
        <div id="selectedQuestionsList">
            <!-- Selected questions will appear here -->
        </div>
        <button type="button" class="btn btn-primary mt-3" id="confirmSelectionBtn">Confirm Selection</button>
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
<script src="{{ asset('assets/summernote/summernote-lite.js') }}"></script>

<script>
    $(document).ready(function () {
        let selectedQuestions = []; // Stores IDs of selected questions
        let allQuestions = []; // Stores all questions loaded for the current department/programme
        let currentSelectionMode = 'manual'; // 'manual' or 'random'
        let manualQuestionCount = 0; // Stores the number of questions for manual selection

        // Get the modal elements
        const modal = $('#selectionModal');
        const span = $('.close-button');
        const selectedQuestionsList = $('#selectedQuestionsList');
        const confirmSelectionBtn = $('#confirmSelectionBtn');
        const reviewSelectionBtn = $('#reviewSelectionBtn');
        const randomCountContainer = $('#randomCountContainer');
        const questionCountInput = $('#question_count');

        // When the user clicks on <span> (x), close the modal
        span.on('click', function() {
            modal.hide();
        });

        // When the user clicks anywhere outside of the modal, close it
        $(window).on('click', function(event) {
            if (event.target == modal[0]) {
                modal.hide();
            }
        });

        // --- Selection Mode Toggling ---
        $('#manualSelectionBtn').on('click', function() {
            currentSelectionMode = 'manual';
            $('#selectionModeInput').val('manual');
            $(this).addClass('active');
            $('#randomSelectionBtn').removeClass('active');
            randomCountContainer.addClass('active');
            $('.question-checkbox').prop('disabled', false);
            $('#selectAll').prop('disabled', false);
            questionCountInput.val(manualQuestionCount > 0 ? manualQuestionCount : '');
            resetQuestionSelection();
        });

        $('#randomSelectionBtn').on('click', function() {
            currentSelectionMode = 'random';
            $('#selectionModeInput').val('random');
            $(this).addClass('active');
            $('#manualSelectionBtn').removeClass('active');
            randomCountContainer.addClass('active');
            $('.question-checkbox').prop('disabled', true);
            $('#selectAll').prop('disabled', true);
            resetQuestionSelection();
            questionCountInput.val('');
        });

        // --- Helper function to reset question selections ---
        function resetQuestionSelection() {
            selectedQuestions = [];
            $('.question-checkbox').prop('checked', false);
            $('tr').removeClass('selected');
            $('#selectAll').prop('checked', false);
        }

        // --- Load programmes when department changes ---
        $('#department_select').on('change', function() {
            let departmentId = $(this).val();
            let programmeSelect = $('#programme_select');
            
            // Clear current programme options
            programmeSelect.empty().append('<option value="">Select Programme</option>');
            
            if (departmentId) {
                $.ajax({
                    url: '{{ route("get.programmes.by.department") }}',
                    type: 'GET',
                    data: { department_id: departmentId },
                    success: function(response) {
                        if (response.length > 0) {
                            $.each(response, function(index, programme) {
                                programmeSelect.append(
                                    `<option value="${programme.id}">${programme.title}</option>`
                                );
                            });
                        } else {
                            programmeSelect.append('<option value="">No programmes found</option>');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('Error loading programmes. Please try again.');
                    }
                });
            }
            
            // Clear the questions table when department changes
            $('#department-data-table tbody').empty();
            $('#totalQuestions').text('0');
            questionCountInput.val('');
            resetQuestionSelection();
        });

        // --- Load questions when programme changes ---
        $('#programme_select').on('change', function() {
            let departmentId = $('#department_select').val();
            let programmeId = $(this).val();
            
            if (departmentId && programmeId) {
                loadQuestions(departmentId, programmeId);
            } else {
                $('#department-data-table tbody').empty();
                $('#totalQuestions').text('0');
                questionCountInput.val('');
                resetQuestionSelection();
            }
        });

        // --- Function to load questions ---
        function loadQuestions(departmentId, programmeId) {
            $.ajax({
                url: '{{ route("get.paper.data") }}',
                type: 'GET',
                data: { department_id: departmentId, programme_id: programmeId },
                success: function(response) {
                    let tbody = $('#department-data-table tbody');
                    tbody.empty();
                    resetQuestionSelection();
                    allQuestions = response;

                    if (response.length > 0) {
                        $('#totalQuestions').text(response.length);

                        $.each(response, function(index, item) {
                            tbody.append(
                                `<tr data-id="${item.id}">
                                    <td class="checkbox-cell">
                                        <input type="checkbox" class="checkbox-input question-checkbox" value="${item.id}" ${currentSelectionMode === 'random' ? 'disabled' : ''}>
                                    </td>
                                    <td>${item.id}</td>
                                    <td>${item.question_title}</td>
                                    <td>${item.option_A}, ${item.option_B}, ${item.option_C}, ${item.option_D}</td>
                                </tr>`
                            );
                        });
                        
                        if (currentSelectionMode === 'random') {
                            $('#selectAll').prop('disabled', true);
                        } else {
                            $('#selectAll').prop('disabled', false);
                        }
                    } else {
                        tbody.append('<tr><td colspan="4" class="text-center">No data found</td></tr>');
                        $('#totalQuestions').text('0');
                        questionCountInput.val('');
                        resetQuestionSelection();
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Error loading questions. Please try again.');
                    $('#department-data-table tbody').empty();
                    $('#totalQuestions').text('0');
                    questionCountInput.val('');
                    resetQuestionSelection();
                }
            });
        }

        // --- Select all checkbox ---
        $('#selectAll').on('change', function() {
            if (currentSelectionMode === 'manual') {
                const isChecked = $(this).prop('checked');

                if (isChecked && allQuestions.length > 0 && manualQuestionCount === 0) {
                    alert('Please enter the number of questions to select manually first.');
                    $(this).prop('checked', false);
                    return;
                }

                if (isChecked && allQuestions.length > manualQuestionCount && manualQuestionCount > 0) {
                    alert(`You can only select ${manualQuestionCount} questions. Please uncheck some or adjust your desired count.`);
                    $(this).prop('checked', false);
                    return;
                }

                $('.question-checkbox').prop('checked', isChecked);
                $('.question-checkbox').each(function() {
                    const questionId = $(this).val();
                    const row = $(this).closest('tr');
                    if (isChecked && !selectedQuestions.includes(questionId)) {
                        selectedQuestions.push(questionId);
                        row.addClass('selected');
                    } else if (!isChecked && selectedQuestions.includes(questionId)) {
                        selectedQuestions = selectedQuestions.filter(id => id !== questionId);
                        row.removeClass('selected');
                    }
                });
            }
        });

        // --- Individual checkbox selection ---
        $(document).on('change', '.question-checkbox', function() {
            if (currentSelectionMode === 'manual') {
                const questionId = $(this).val();
                const isChecked = $(this).prop('checked');
                const row = $(this).closest('tr');

                if (manualQuestionCount === 0) {
                    alert('Please enter the number of questions you want to select manually.');
                    $(this).prop('checked', false);
                    row.removeClass('selected');
                    return;
                }

                if (isChecked) {
                    if (selectedQuestions.length >= manualQuestionCount) {
                        alert(`You can only select ${manualQuestionCount} questions.`);
                        $(this).prop('checked', false);
                        row.removeClass('selected');
                        return;
                    }
                    if (!selectedQuestions.includes(questionId)) {
                        selectedQuestions.push(questionId);
                        row.addClass('selected');
                    }
                } else {
                    selectedQuestions = selectedQuestions.filter(id => id !== questionId);
                    row.removeClass('selected');
                    if ($('.question-checkbox:checked').length !== $('.question-checkbox').length) {
                        $('#selectAll').prop('checked', false);
                    }
                }
            }
        });

        // --- Handle manual/random selection when number input changes ---
        questionCountInput.on('input', function() {
            let count = parseInt($(this).val());
            const totalQuestions = allQuestions.length;

            if (isNaN(count) || count < 0) {
                count = 0;
            } else if (count > totalQuestions) {
                alert(`You can't select more questions than available. Total available questions: ${totalQuestions}`);
                $(this).val(totalQuestions);
                count = totalQuestions;
            }

            if (currentSelectionMode === 'manual') {
                manualQuestionCount = count;
                if (selectedQuestions.length > manualQuestionCount && manualQuestionCount > 0) {
                    alert(`You have selected ${selectedQuestions.length} questions, but your new limit is ${manualQuestionCount}. Please uncheck some questions.`);
                } else if (manualQuestionCount === 0) {
                    resetQuestionSelection();
                }
                $('.question-checkbox').prop('disabled', false);
                $('#selectAll').prop('disabled', false);

            } else if (currentSelectionMode === 'random') {
                selectedQuestions = [];
                $('.question-checkbox').prop('checked', false);
                $('tr').removeClass('selected');

                if (count > 0) {
                    const shuffled = [...allQuestions].sort(() => 0.5 - Math.random());
                    const selected = shuffled.slice(0, count);

                    selected.forEach(question => {
                        selectedQuestions.push(question.id.toString());
                        $(`tr[data-id="${question.id}"]`).addClass('selected');
                        $(`tr[data-id="${question.id}"] .question-checkbox`).prop('checked', true);
                    });
                }
            }
        });

        // --- Open the review modal ---
        reviewSelectionBtn.on('click', function() {
            const deptId = $('#department_select').val();
            const progId = $('#programme_select').val();

            if (!deptId || !progId) {
                alert('Please select both Department and Programme.');
                return false;
            }

            let isValidSelection = true;

            if (currentSelectionMode === 'random') {
                const requestedCount = parseInt(questionCountInput.val());
                const totalQuestions = allQuestions.length;

                if (isNaN(requestedCount) || requestedCount <= 0) {
                    alert('Please enter a valid number of questions for random selection.');
                    isValidSelection = false;
                } else if (requestedCount > totalQuestions) {
                    alert(`You can't select more questions than available. Total available questions: ${totalQuestions}`);
                    isValidSelection = false;
                }
                if (isValidSelection && selectedQuestions.length === 0 && requestedCount > 0) {
                    const shuffled = [...allQuestions].sort(() => 0.5 - Math.random());
                    const selected = shuffled.slice(0, requestedCount);
                    selectedQuestions = selected.map(q => q.id.toString());
                }

            } else {
                if (manualQuestionCount === 0 || isNaN(manualQuestionCount)) {
                    alert('Please enter the number of questions you want to select for manual selection.');
                    isValidSelection = false;
                } else if (selectedQuestions.length !== manualQuestionCount) {
                    alert(`You must select exactly ${manualQuestionCount} questions. You have selected ${selectedQuestions.length}.`);
                    isValidSelection = false;
                }
            }

            if (!isValidSelection) {
                return false;
            }

            populateSelectedQuestionsModal();
            modal.show();
        });

        // --- Function to populate the modal with selected questions ---
        function populateSelectedQuestionsModal() {
            selectedQuestionsList.empty();

            if (selectedQuestions.length === 0) {
                selectedQuestionsList.append('<p>No questions selected.</p>');
                return;
            }

            selectedQuestions.forEach(qId => {
                const question = allQuestions.find(item => item.id == qId);
                if (question) {
                    selectedQuestionsList.append(
                        `<div class="selected-question-item" data-id="${question.id}">
                            <span>${question.question_title}</span>
                            <button type="button" class="remove-question-btn" data-id="${question.id}">
                                &times; Remove
                            </button>
                        </div>`
                    );
                }
            });
        }

        // --- Remove question from modal and update main table/array ---
        $(document).on('click', '.remove-question-btn', function() {
            const questionIdToRemove = $(this).data('id').toString();

            selectedQuestions = selectedQuestions.filter(id => id !== questionIdToRemove);
            $(`.selected-question-item[data-id="${questionIdToRemove}"]`).remove();
            $(`tr[data-id="${questionIdToRemove}"] .question-checkbox`).prop('checked', false);
            $(`tr[data-id="${questionIdToRemove}"]`).removeClass('selected');

            if ($('.question-checkbox:checked').length !== $('.question-checkbox').length) {
                $('#selectAll').prop('checked', false);
            }

            if (selectedQuestions.length === 0) {
                selectedQuestionsList.append('<p>No questions selected.</p>');
            }
        });

        // --- Confirm selection from modal and submit the form ---
        confirmSelectionBtn.on('click', function() {
            const deptId = $('#department_select').val();
            const progId = $('#programme_select').val();

            if (selectedQuestions.length === 0) {
                alert('Please select at least one question before confirming.');
                return;
            }

            $('#hidden_department_id').val(deptId);
            $('#hidden_programme_id').val(progId);
            $('#selectedQuestionsInput').val(JSON.stringify(selectedQuestions));
            $('#selectionModeInput').val(currentSelectionMode);

            modal.hide();
            $('#selectedQuestionsForm').submit();
        });

        // Initial state
        randomCountContainer.addClass('active');
    });
</script>

<script>
    document.getElementById('programmes_select').addEventListener('change', function () {
        document.getElementById('hidden_programmes_id').value = this.value;
    });

    // Optional: Set the initial value in case the dropdown already has a value
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('hidden_programmes_id').value = document.getElementById('programmes_select').value;
    });
</script>


@endsection
