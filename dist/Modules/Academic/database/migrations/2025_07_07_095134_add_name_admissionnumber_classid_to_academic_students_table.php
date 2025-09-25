use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNameAdmissionnumberClassidToAcademicStudentsTable extends Migration
{
    public function up()
    {
        Schema::table('academic_students', function (Blueprint $table) {
            $table->string('stream')->nullable()->after('previous_school');
            $table->string('house')->nullable()->after('stream');
            $table->string('group')->nullable()->after('house');
            $table->boolean('is_transfer')->default(false)->after('group');
        });
    }

    public function down()
    {
        Schema::table('academic_students', function (Blueprint $table) {
            $table->dropColumn(['stream', 'house', 'group', 'is_transfer']);
        });
    }
} 