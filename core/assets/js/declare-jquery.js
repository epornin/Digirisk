"use strict";

jQuery(document).ready(function( $ ) {
	digi_global.init( $ );
	legal_display.init( $ );
	digi_recommendation.init( $ );
	digi_risk.init( $ );

	digi_installer.event( $ );
	digi_global.event( $ );
	digi_evaluation_method.event( $ );
	digi_evaluator.event( $ );
	file_management.event( $ );
	wpeo_gallery.event( $ );
	digi_group.event( $ );
	digi_society.event( $ );
	digi_workunit.event( $ );
	digi_tab.event( $ );
	digi_user.event( $ );
} );
