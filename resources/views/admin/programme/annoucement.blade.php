<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Training Programme Invitation</title>

  <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}" crossorigin="anonymous"></script>
  <script src="{{ asset('assets/js/popper.min.js') }}" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-4.4.1.min.css') }}" crossorigin="anonymous">
  <script src="{{ asset('assets/js/bootstrap-4.4.1.min.js') }}" crossorigin="anonymous"></script>

  <!-- Summernote CSS & JS -->
  <link href="{{ asset('assets/summernote/summernote-bs4.css') }}" rel="stylesheet">
  <script src="{{ asset('assets/summernote/summernote-bs4.min.js') }}"></script>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Georgia', 'Times New Roman', serif;
      background: #f5f7fa;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .container {
      background: #fff;
      padding: 40px 30px;
      max-width: 800px;
      width: 90%;
      margin: 40px auto;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      border-radius: 10px;
      border: 2px solid #ccc;
      flex-grow: 1;
      overflow: hidden;
    }
    .logo {
      text-align: center;
      margin-bottom: 15px;
    }
    .logo img {
      width: 120px;
      height: auto;
    }
    .header {
      text-align: center;
      font-weight: bold;
      font-size: 24px;
      margin-bottom: 5px;
      color: #003366;
    }
    .sub-header {
      text-align: center;
      font-size: 16px;
      color: #555;
      margin-bottom: 30px;
    }
    .details {
      font-size: 15px;
      margin-bottom: 20px;
    }
    .details span {
      font-weight: bold;
    }
    .subject {
      font-weight: bold;
      margin: 40px 0 20px;
      font-size: 20px;
      text-align: center;
      color: #000;
    }
    .content {
      font-size: 15px;
      text-align: justify;
    }
    .signature {
      margin-top: 10px;
      font-size: 15px;
    }
    .signature p {
      margin: 5px 0;
    }
    .footer {
      margin-top: 5px;
      border-top: 20px solid #000;
      font-size: 14px;
      padding-top: 10px;
    }
    a {
      color: #003366;
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
    .edit-button, .save-button, .cancel-button, .language-toggle {
      padding: 10px 15px;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      margin-right: 10px;
      margin-bottom: 20px;
    }
    .edit-button { 
      background-color: #003366; 
      color: #fff; 
    }
    .save-button { 
      background-color: #4CAF50; 
      color: #fff; 
    }
    .cancel-button { 
      background-color: #f44336; 
      color: #fff; 
    }
    .language-toggle {
      background-color: #6c757d;
      color: #fff;
    }
    .edit-form {
      display: none;
      margin-top: 20px;
    }
    .letterhead {
      border-bottom: 20px solid #000;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }
    .letterhead h2, .letterhead h3 {
      margin: 0;
      font-size:16px;
    }
    .language-content {
      display: none;
    }
    .language-content.active {
      display: block;
    }
    @media print {
      body {
          font-family: 'Georgia', 'Times New Roman', serif;
          font-size: 14px;
          margin: 0;
          padding: 0;
      }
      .letterhead {
          border-bottom: 20px solid #000;
          padding-bottom: 10px;
          margin-bottom: 20px;
      }
      .footer {
          border-top: 20px solid #000;
          padding-top: 10px;
      }
      .container {
          padding: 40px 30px;
          max-width: 800px;
          margin: 0 auto;
          box-shadow: none;
          border: none;
          background: none;
      }
      button {
          display: none;
      }
    }
  </style>
</head>
<body>
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="container">
  <!-- Action Buttons -->
  <div class="action-buttons">
    <button class="language-toggle" onclick="toggleLanguage('english')">English</button>
    <button class="language-toggle" onclick="toggleLanguage('hindi')">हिंदी</button>
    <button class="edit-button" onclick="enableEdit()">Edit</button>
 <form action="{{ route('programme.approve', $programme->id) }}" method="POST" style="display: inline;">
    @csrf
    <button type="submit" class="btn btn-primary">Approve</button>
</form>


    
    <button onclick="printPage()" class="save-button">Print</button>
  </div>

  <!-- View Mode -->
  <div id="contentContainer">
    <!-- English Content -->
    <div id="english-content" class="language-content active">
      @if($programme->content)
        {!! $programme->content !!}
      @else
        <div style="display: flex; align-items: center; justify-content: space-between;" class="letterhead">
          <div>
            <h2>बैंकर्स ग्रामिण विकास संस्थान<small style="font-weight: normal;">      नाबार्ड द्वारा प्रवर्तित आईएसओ 9001:2015 प्रमाणित स्वायत्त संस्था</small></h2>
            <span> <h3>Bankers Institute of Rural Development <small style="font-weight: normal;">An ISO 9001:2015 certified autonomous institute promoted by NABARD</small></h3></span>
          </div>
          <div>
            <img src="{{asset('assets/images/logo.png')}}" alt="Logo" style="height: 80px; margin-left:0px">
          </div>
        </div>
        
        <div class="details">
          BIRD.LKO./<span>19676-20140</span> / 46015 / DA&FS/2024-25<br>
          <span>{{ \Carbon\Carbon::parse($programme->created_at)->format('d-m-Y') }}</span>
        </div>
        
        <div class="details">
          All <span>{{$agencyTypes->name}}/</span>
        </div>
        
        <div class="details">
          Madam/Dear Sir,
        </div>
        
        <div class="subject">
          Training Programme on {{$programme->title}} from {{ \Carbon\Carbon::parse($programme->from_date)->format('d F Y') }} to {{ \Carbon\Carbon::parse($programme->to_date)->format('d F Y') }} at BIRD, Lucknow
        </div>
        
        <div class="content" id="contentEditable">
          Data Analytics powered by Artificial Intelligence and Machine Learning have enabled banks to improve customer experience, enhance security, manage risk effectively, increase operational efficiency, gain a competitive edge, innovate, and implement new business models for better service delivery.<br><br>
          
          In order to introduce bankers to the emerging applications of AI & ML in the financial sector and its impact on business growth, Bankers Institute of Rural Development (BIRD), Lucknow is organizing a Training Programme on <strong>"{{$programme->title}}"</strong> from <strong>{{ \Carbon\Carbon::parse($programme->from_date)->format('d F Y') }} to {{ \Carbon\Carbon::parse($programme->to_date)->format('d F Y') }}</strong> at {{$programme->venue}}. The learnings from this programme can be a catalyst to generate innovative ideas in different business areas like customer acquisition, lending, risk mitigation, etc.<br><br>
          
          Normally, the course fee for the programme is <strong>{{$programme->fee_structure}}</strong> per participant. Since the <strong>programme is sponsored by {{$sponsor->name}}</strong>,
          <span> 
              @if($sponsor->name === 'NABARD')
                  <strong>No participation fees will be charged by BIRD.</strong>
              @endif
          </span>
          
          However, travelling expenses will have to be borne by the respective sponsoring bank.<br><br>
          
          There are only limited seats, which will be filled up on a first-come-first-served basis, subject to fulfilling eligibility criteria. Considering the importance and topical relevance of the programme, we request you to nominate officers of your bank for the programme and forward the nominations (in the prescribed format) as early as possible, latest by <strong>{{ \Carbon\Carbon::parse($programme->last_nomination_date)->format('d F Y') }} </strong>.
        </div>
        
        <div class="signature">
          Yours faithfully,<br><br><br><br>
          <strong>{{$users1->name}} & {{$users2->name}}</strong><br>
          <strong>Program Directors</strong><br>
          Encl.: Program brochure
        </div>
        
        <div class="footer">
          सेटर-एच, एलडीए कॉलोनी, कानपुर रोड, लखनऊ – 226012<br>
          Sector-H, LDA Colony, Kanpur Road, Lucknow – 226012<br>
          Phone: +91-522-2425917 / 2421097 | Email: bird@nabard.org | Website: <a href="https://birdlucknow.nabard.org" target="_blank">birdlucknow.nabard.org</a>
        </div>
      @endif
    </div>

    <!-- Hindi Content -->
    <div id="hindi-content" class="language-content">
      @if($programme->hindi_content)
        {!! $programme->hindi_content !!}
      @else
        <div style="display: flex; align-items: center; justify-content: space-between;" class="letterhead">
          <div>
            <h2>बैंकर्स ग्रामीण विकास संस्थान 
              <small style="font-weight: normal;">नाबार्ड द्वारा प्रवर्तित आईएसओ 9001:2015 प्रमाणित स्वायत्त संस्था</small>
            </h2>
            <h3>Bankers Institute of Rural Development 
              <small style="font-weight: normal;">An ISO 9001:2015 certified autonomous institute promoted by NABARD</small>
            </h3>
          </div>
          <div>
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" style="height: 80px; margin-left:0px">
          </div>
        </div>
        
        <div class="details">
          BIRD.LKO./<span>19676-20140</span> / 46015 / DA&FS/2024-25<br>
          <span>{{ \Carbon\Carbon::parse($programme->created_at)->locale('hi')->translatedFormat('d F Y') }}</span>
        </div>
        
        <div class="details">
          सभी <span>{{ $agencyTypes->hindi_name }}</span>
        </div>
        
        <div class="details">
          महोदय/महोदया,
        </div>
        
        <div class="subject">
          प्रशिक्षण कार्यक्रम: "{{ $programme->hindi_title }}" दिनांक 
          {{ \Carbon\Carbon::parse($programme->from_date)->locale('hi')->translatedFormat('d F Y') }} से 
          {{ \Carbon\Carbon::parse($programme->to_date)->locale('hi')->translatedFormat('d F Y') }} तक, स्थान: BIRD, लखनऊ
        </div>
        
        <div class="content" id="contentEditable">
          कृत्रिम बुद्धिमत्ता और मशीन लर्निंग से समर्थित डेटा एनालिटिक्स ने बैंकों को ग्राहक अनुभव बढ़ाने, सुरक्षा मजबूत करने, जोखिम प्रबंधन में सुधार, संचालन में दक्षता, प्रतिस्पर्धात्मक लाभ, नवाचार, और बेहतर सेवा वितरण के लिए नए व्यवसाय मॉडल लागू करने में सक्षम बनाया है।<br><br>
          
          बैंकों के अधिकारियों को वित्तीय क्षेत्र में AI और ML के उपयोग और उनके व्यापारिक प्रभाव से परिचित कराने के उद्देश्य से, बैंकर्स इंस्टीट्यूट ऑफ रूरल डेवलपमेंट (BIRD), लखनऊ द्वारा <strong>"{{ $programme->hindi_title }}"</strong> नामक प्रशिक्षण कार्यक्रम 
          <strong>{{ \Carbon\Carbon::parse($programme->from_date)->locale('hi')->translatedFormat('d F Y') }} से {{ \Carbon\Carbon::parse($programme->to_date)->locale('hi')->translatedFormat('d F Y') }}</strong> तक 
          {{ $programme->venue }} में आयोजित किया जा रहा है। इस कार्यक्रम से प्राप्त ज्ञान विभिन्न व्यवसायिक क्षेत्रों जैसे ग्राहक प्राप्ति, ऋण, जोखिम प्रबंधन आदि में नवाचार उत्पन्न कर सकता है।<br><br>
          
          सामान्यतः इस कार्यक्रम का शुल्क प्रति प्रतिभागी <strong>{{ $programme->fee_structure }}</strong> है। चूंकि यह कार्यक्रम <strong>{{ $sponsor->hindi_name ?? $sponsor->name }}</strong> द्वारा प्रायोजित है,
          @if($sponsor->name === 'NABARD')
            <strong>अतः BIRD द्वारा कोई प्रतिभागी शुल्क नहीं लिया जाएगा।</strong>
          @endif
          हालांकि, यात्रा व्यय संबंधित प्रायोजक बैंक द्वारा वहन किया जाएगा।<br><br>
          
          सीटें सीमित हैं और पात्रता मापदंडों की पूर्ति पर पहले आओ-पहले पाओ के आधार पर भरी जाएंगी। कार्यक्रम के महत्व को ध्यान में रखते हुए, आपसे अनुरोध है कि अपने बैंक के अधिकारियों का नामांकन शीघ्र करें एवं अधिकतम <strong>{{ \Carbon\Carbon::parse($programme->last_nomination_date)->locale('hi')->translatedFormat('d F Y') }}</strong> तक भेजें।
        </div>
        
        <div class="signature">
          सादर,<br><br><br>
          <strong>{{ $users1->hindi_name }} एवं {{ $users2->hindi_name }}</strong><br>
          कार्यक्रम निदेशक<br>
          संलग्न: कार्यक्रम विवरणिका
        </div>
        
        <div class="footer">
          सेटर-एच, एलडीए कॉलोनी, कानपुर रोड, लखनऊ – 226012<br>
          Sector-H, LDA Colony, Kanpur Road, Lucknow – 226012<br>
          फोन: +91-522-2425917 / 2421097 | ईमेल: bird@nabard.org | वेबसाइट: 
          <a href="https://birdlucknow.nabard.org" target="_blank">birdlucknow.nabard.org</a>
        </div>
      @endif
    </div>
  </div>

  <!-- Edit Mode -->
  <form id="editorForm" class="edit-form" method="POST" action="{{route('announcement.edit.store',$programme->id)}}">
    @csrf
    <div class="form-group">
      <label>Select Language to Edit:</label>
      <select id="languageSelect" class="form-control" onchange="changeEditLanguage()">
        <option value="english">English</option>
        <option value="hindi">Hindi</option>
      </select>
    </div>
    
    <div id="english-editor">
      <textarea id="summernote-english" name="content">{!! old('content', $programme->content ?? $defaultContent) !!}</textarea>
    </div>
    
    <div id="hindi-editor" style="display:none;">
      <textarea id="summernote-hindi" name="hindi_content">{!! old('hindi_content', $programme->hindi_content ??$defaultContentHindi) !!}</textarea>
    </div>

    <br>
    <button type="submit" class="save-button">Save</button>
    <button type="button" class="cancel-button" onclick="cancelEdit()">Cancel</button>
  </form>
</div>

<script>
  // Language toggle function
  function toggleLanguage(lang) {
    document.querySelectorAll('.language-content').forEach(content => {
      content.classList.remove('active');
    });
    document.getElementById(lang + '-content').classList.add('active');
  }

  // Edit mode functions
  function enableEdit() {
    $('#contentContainer').hide();
    $('#editorForm').show();
    
    // Initialize the editor for the current language
    if ($('#languageSelect').val() === 'english') {
      $('#summernote-english').summernote({
        height: 500,
        focus: true,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });
    } else {
      $('#summernote-hindi').summernote({
        height: 500,
        focus: true,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });
    }
  }

  function cancelEdit() {
    $('#editorForm').hide();
    $('#summernote-english').summernote('destroy');
    $('#summernote-hindi').summernote('destroy');
    $('#contentContainer').show();
  }

  function changeEditLanguage() {
    const lang = $('#languageSelect').val();
    if (lang === 'english') {
      $('#english-editor').show();
      $('#hindi-editor').hide();
      
      // Initialize English editor if not already initialized
      if (!$('#summernote-english').summernote('isEmpty')) {
        $('#summernote-english').summernote({
          height: 500,
          focus: true,
          toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
          ]
        });
      }
    } else {
      $('#english-editor').hide();
      $('#hindi-editor').show();
      
      // Initialize Hindi editor if not already initialized
      if (!$('#summernote-hindi').summernote('isEmpty')) {
        $('#summernote-hindi').summernote({
          height: 500,
          focus: true,
          toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
          ]
        });
      }
    }
  }

  function printPage() {
    var content = document.querySelector('.language-content.active').innerHTML;
    
    var printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Print</title>');
    printWindow.document.write('<style>body { font-family: Georgia, Times, serif; font-size: 14px; }</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(content);
    printWindow.document.write('</body></html>');
    
    printWindow.document.close();
    printWindow.print();
  }
</script>
</body>
</html>