<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Acaunt extends Model {
	//

	public function proxy() {
		return $this->belongsTo('App\Proxy');
	}
}
