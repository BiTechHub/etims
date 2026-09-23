<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\AgencyType;


class ProgrammeManagement extends Model

{

    use HasFactory, SoftDeletes;



    protected $table = 'programmes';



    protected $fillable = [

        'group_id',

        'programme_id',

        'program_title',

        'group',

        'announcement_status',

        'area',

        'title',

        'location',

        'venue',

        'sponsor_id',

        'department_name',

        'department_input',

        'participant_fee_check',

        'participant_fee',

        'program_fee_check',

        'program_fee',

        'from_date',

        'to_date',

        'duration',

        'session_start',  

        'session_end',

        'fee_structure',

        'announcement_letter_path',

        'nomination_form_path',

        'pcr_path',

        'faculty1_id',

        'faculty2_id',

        'class_id',

        'department_id',

        'status',

        'announced_on',

        'last_nomination',

        'last_nomination_date',

        'strength',

        'class_room_id',

        'boarding_plan',

        'max_disc_amt',

        'prog_dir_1',

        'prog_dir_2',

        'clientele_type',

        'clientele',

        'claim_ref',

        'remarks',

        'announcement_letter',

        'nomination_form',

        'pcr',

        'created_at',

        'updated_at',

        'agency_type_id',

        'hindi_title',

        'financial_year',

        'is_active',

        'bed_id',

        'manual_file',

        'announcement_type',

        'content',

        'program_type',
        'guest_faculty',

        'unique_id',
        // 'is_aur_child',

    ];





    protected $casts = [

        'participant_fee_check' => 'boolean',

        'program_fee_check' => 'boolean',

        'from_date' => 'date',

        'to_date' => 'date',

        'session_start' => 'datetime',

        'session_end' => 'datetime',

        'agency_type_id' => 'array',

         'announced_on' => 'array',
        
    ];

    protected $dates = ['created_at', 'updated_at'];

    // Relationships

    public function group()

    {

        return $this->belongsTo(Group::class)->select('id', 'name');

    }



    public function sponsor()

    {

        return $this->belongsTo(Sponsor::class)->select('id', 'name' ,'type');

    }



    public function department()

    {

        return $this->belongsTo(Department::class)->select('id', 'name');

    }

    public function progDir1()

    {

        return $this->belongsTo(User::class, 'prog_dir_1','id');

    }

    public function classRoom()

    {

        return $this->belongsTo(Classes::class, 'class_room_id');

    }



    public function progDir2()

    {

        return $this->belongsTo(User::class, 'prog_dir_2','id');

    }



public function agencyType()
{
    return $this->belongsTo(AgencyType::class, 'agency_type_id', 'id');
}



public function agencyFees()

{

    return $this->hasMany(ProgrammeAgencyFee::class, 'programme_id');

}



    // Accessors for file URLs

    public function getAnnouncementLetterUrlAttribute()

    {

        return $this->announcement_letter_path 

            ? asset('storage/' . $this->announcement_letter_path)

            : null;

    }



    public function getNominationFormUrlAttribute()

    {

        return $this->nomination_form_path 

            ? asset('storage/' . $this->nomination_form_path)

            : null;

    }



    public function getPcrUrlAttribute()

    {

        return $this->pcr_path 

            ? asset('storage/' . $this->pcr_path)

            : null;

    }

    // App\Models\Programme.php



public function nominations()

{

    return $this->hasMany(Nomination::class);

}

public function participants()
{
    return $this->hasMany(Participant::class, 'programme_id');
}

public function subtopics()
{
    return $this->hasMany(\App\Models\Subtopic::class, 'programme_id');
}

public function questionPaperBasicDetail()
{
    return $this->hasOne(\App\Models\QuestionPaperBasicDetail::class, 'programme_id');
}
}



// protected static function boot()
// {
//     parent::boot();

//     static::saving(function ($programme) {
//         if ($programme->unique_id && $programme->financial_year) {
//             $programme->is_aur_child = $programme->unique_id . '-' . $programme->financial_year;
//         }
//     });
// }


