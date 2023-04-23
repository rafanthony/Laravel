<script>
	var cusData = [
		{
			TYPE     			: "Existing",
			FNAME    			: "Raf Anthony",
			LNAME      	        : "Masbate",
            CUSTOMER_ID      	: "100001",
			EMAIL     		    : "example@123.com",
			PHONENUM			: "01234567890",
			GENDER 				: "Male",
			OCCUPATION 			: "Web Devekoper",
			DATE_BIRTH			: "23/10/2000",
			BILLING				: "Mandaue St. Cebu City",
			PURCHASE_HIS		: "Old",
			PAYMENT				: "Cash",
			ORDER    			: "00101",
			COMPANY 			: "TELC",
			DEL_FLAG    		: true
		}
	];

	const bpDataOrganizer = {
		_filteredById : function(id){
			filteredBP = [];
			for(let i=0; i<cusData.length; i++){
				if(cusData[i].CUSTOMER_ID == id){
					filteredBP.push(cusData[i]);
				}
			}
			return filteredBP;
		},
		_updateById : function(id){
			let busyDialog = showBusyDialog("Please wait loading..");
				busyDialog.open();
			
			cusData.map(ci_id => {
				if(ci_id.CUSTOMER_ID == id) {
				 
						ci_id.TYPE     			= ui('CUS_TYPE_INFO').getSelectedKey();
						ci_id.FNAME    	    	= ui('CUS_FNAME').getValue().trim();
						ci_id.CUSTOMER_ID      	= ui('CUS_ID').getValue().trim();
						ci_id.LNAME      	    = ui('CUS_LNAME').getValue().trim();
						ci_id.EMAIL           	= ui('EMAIL_ADD').getValue().trim();
						ci_id.PHONENUM			= ui('CUS_PNUM').getValue().trim();
						ci_id.GENDER			= ui('CUS_GENDER').getValue().trim();
						ci_id.OCCUPATION     	= ui('CUS_OCCUP').getSelectedKey();
						ci_id.DATE_BIRTH		= ui('CUS_DOB').getValue().trim();
						ci_id.BILLING			= ui('CUS_BILL').getValue().trim();
						ci_id.PURCHASE_HIS		= ui('CUS_HIS').getValue().trim();
						ci_id.PAYMENT			= ui('CUS_PAYMETHOD').getSelectedKey();
						ci_id.ORDER				= ui('CUS_ORDER').getValue().trim();
						ci_id.COMPANY			= ui('CUS_COMPNAME').getValue().trim();
						ci_id.DEL_FLAG    		= ui('CONTROL_INFO_DEL_FLAG').getState();
				}
				
			});
			screenMode._display(id);
			listingBp._getData(cusData);
			setTimeout(() => {busyDialog.close();}, 2000);
		},
	
		_validateBP : function(id){
			let isExist = false;
				for(let i=0; i<cusData.length; i++){
				if(cusData[i].CUSTOMER_ID == id){
					isExist = true;
					break;
				}
			}
				return isExist;
			}
		}

	const screenMode = {
		_id : "",
		_title : "",
		_mode : "",
		_create : function(){
			this._mode = "create";
			let bp_title = this._title;
			bp_title = "Create Customer Infomation";
			this._clear();
			//Buttons
			ui("CREATE_CUS_SAVE_BTN").setVisible(true);
			ui("CREATE_CUS_EDIT_BTN").setVisible(false);
			ui("CREATE_CUS_CANCEL_BTN").setVisible(false);

			//title and crumbs
			ui('CUS_TITLE').setText(bp_title);
			ui('CREATE_CUS_BRDCRMS').setCurrentLocationText(bp_title);
			ui("PANEL_FORM").setTitle("New Customer Infomation");

			//Fields
			ui('CUS_TYPE_INFO').setEditable(true);
			ui('CUS_FNAME').setEditable(true);
			ui('CUS_ID').setEditable(true);
			ui('CUS_LNAME').setEditable(true);
			ui('EMAIL_ADD').setEditable(true);
			ui('CUS_PNUM').setEditable(true);
			ui('CUS_GENDER').setEditable(true);
			ui('CUS_OCCUP').setEditable(true);
			ui('CUS_DOB').setEditable(true);
			ui('CUS_BILL').setEditable(true);
			ui('CUS_HIS').setEditable(true);
			ui('CUS_PAYMETHOD').setEditable(true);
			ui('CUS_ORDER').setEditable(true);
			ui('CUS_COMPNAME').setEditable(true);
			ui('CONTROL_INFO_DEL_FLAG').setEnabled(true);

			go_App_Right.to('CREATE_BP_PAGE');
		},
		_edit : function(){
			this._mode = "edit";
			//Buttons
			ui("CREATE_CUS_SAVE_BTN").setVisible(true);
			ui("CREATE_CUS_EDIT_BTN").setVisible(false);
			ui("CREATE_CUS_CANCEL_BTN").setVisible(true);

			//Fields
			ui('CUS_TYPE_INFO').setEditable(true);
			ui('CUS_FNAME').setEditable(true);
			ui('CUS_ID').setEditable(false);
			ui('CUS_LNAME').setEditable(true);
			ui('EMAIL_ADD').setEditable(true);
			ui('CUS_PNUM').setEditable(true);
			ui('CUS_GENDER').setEditable(true);
			ui('CUS_OCCUP').setEditable(true);
			ui('CUS_DOB').setEditable(true);
			ui('CUS_BILL').setEditable(true);
			ui('CUS_HIS').setEditable(true);
			ui('CUS_PAYMETHOD').setEditable(true);
			ui('CUS_ORDER').setEditable(true);
			ui('CUS_COMPNAME').setEditable(true);
			ui('CONTROL_INFO_DEL_FLAG').setEnabled(true);
		},
		_display : function(id){
			ui('MESSAGE_STRIP_CUS_ERROR').destroyContent().setVisible(false);
			ui('CUS_ID').setValueState("None").setValueStateText("");
			this._mode = "display";
			this._id = id;
			let bp_title = this._title;
			bp_title = "Display Customer Information";
			//Buttons
			ui("CREATE_CUS_SAVE_BTN").setVisible(false);
			ui("CREATE_CUS_EDIT_BTN").setVisible(true);
			ui("CREATE_CUS_CANCEL_BTN").setVisible(false);


			//fields with value
			let data = bpDataOrganizer._filteredById(id);
			if(data.length > 0){
				ui('CUS_TYPE_INFO').setSelectedKey(data[0].TYPE).setEditable(false);
       			ui('CUS_FNAME').setValue(data[0].FNAME).setEditable(false);
        		ui('CUS_ID').setValue(data[0].CUSTOMER_ID).setEditable(false);
				ui('CUS_LNAME').setValue(data[0].LNAME).setEditable(false);
				ui('EMAIL_ADD').setValue(data[0].EMAIL).setEditable(false);
				ui('CUS_PNUM').setValue(data[0].PHONENUM).setEditable(false);
				ui('CUS_GENDER').setValue(data[0].GENDER).setEditable(false);
				ui('CUS_OCCUP').setSelectedKey(data[0].OCCUPATION).setEditable(false);
				ui('CUS_DOB').setValue(data[0].DATE_BIRTH).setEditable(false);
				ui('CUS_BILL').setValue(data[0].BILLING).setEditable(false);
				ui('CUS_HIS').setValue(data[0].PURCHASE_HIS).setEditable(false);
				ui('CUS_PAYMETHOD').setSelectedKey(data[0].PAYMENT).setEditable(false);
				ui('CUS_ORDER').setValue(data[0].ORDER).setEditable(false);
				ui('CUS_COMPNAME').setValue(data[0].EMAIL).setEditable(false);
				ui('CONTROL_INFO_DEL_FLAG').setState(data[0].DEL_FLAG).setEnabled(false);
			
			
				//title and crumbs
				ui('CUS_TITLE').setText(bp_title);
				ui('CREATE_CUS_BRDCRMS').setCurrentLocationText(bp_title);
				ui("PANEL_FORM").setTitle("Customer Infomation ID "+"("+data[0].CUSTOMER_ID+")");

				go_App_Right.to('CREATE_CUS_PAGE');
			}			
		},
		_clear : function(){
			ui('MESSAGE_STRIP_CUS_ERROR').destroyContent().setVisible(false);
			ui('CUS_TYPE_INFO').setValue("");
			ui('CUS_FNAME').setValue("");
			ui('CUS_ID').setValue("");
			ui('CUS_LNAME').setValue("");
			ui('EMAIL_ADD').setValue("");
			ui('CUS_PNUM').setValue("");
			ui('CUS_GENDER').setValue("");
			ui('CUS_OCCUP').setValue("");
			ui('CUS_DOB').setValue("");
			ui('CUS_BILL').setValue("");
			ui('CUS_HIS').setValue("");
			ui('CUS_PAYMETHOD').setValue("");
			ui('CUS_ORDER').setValue("");
			ui('CUS_COMPNAME').setValue("");
			ui('CONTROL_INFO_DEL_FLAG').setEnabled(true);
		}
	
	
	};

    const createCus = () => {
		let busyDialog = showBusyDialog("Please wait loading..");
		busyDialog.open();
		let data_for_general = {
			TYPE     			: ui('CUS_TYPE_INFO').getSelectedKey(),
			FNAME    			: ui('CUS_FNAME').getValue().trim(),
			CUSTOMER_ID         : ui('CUS_ID').getValue().trim(),
			LNAME            	: ui('CUS_LNAME').getValue().trim(),
			EMAIL     		    : ui('EMAIL_ADD').getValue().trim(),
			PHONENUM			: ui('CUS_PNUM').getValue().trim(),
			GENDER				: ui('CUS_GENDER').getValue().trim(),
			OCCUPATION     		: ui('CUS_OCCUP').getSelectedKey(),
			DATE_BIRTH			: ui('CUS_DOB').getValue().trim(),
			BILLING				: ui('CUS_BILL').getValue().trim(),
			PURCHASE_HIS		: ui('CUS_HIS').getValue().trim(),
			PAYMENT				: ui('CUS_PAYMETHOD').getSelectedKey(),
			ORDER				: ui('CUS_ORDER').getValue().trim(),
			COMPANY				: ui('CUS_COMPNAME').getValue().trim(),
			DEL_FLAG    		: ui('CONTROL_INFO_DEL_FLAG').getState()
   		};
		//add new data to array
		cusData.push(data_for_general);
		screenMode._display(data_for_general.CUSTOMER_ID);
		setTimeout(() => {busyDialog.close();}, 2000);
		
		//commented use for backend only
		/*fetch('/bizpartner/create_data',{
			method : 'POST',
			headers : {
				'Content-Type' : 'application/json',
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			body : JSON.stringify(data_for_general)


		}).then((response) => {
			console.log(response);
			return response.json();
		}).then(data => {
			console.log(data);
		}).catch((err) => {
			console.log("Rejected "+err);
		});*/
        
    }

	const displayBp =  {
		
		_get_data(search){
			
			let busyDialog = showBusyDialog("Please wait loading..");
				busyDialog.open();

				let data = bpDataOrganizer._filteredById(search);
				this._bind_data(data);
			
			
			setTimeout(() => {busyDialog.close();}, 2000);
		},
		_bind_data(data){
		
			ui("DISPLAY_CUS_TABLE").unbindRows();
			
			var lt_model = new sap.ui.model.json.JSONModel();
				lt_model.setSizeLimit(data.length);
				lt_model.setData(data);
				
			ui('DISPLAY_CUS_TABLE').setModel(lt_model).bindRows("/");
			ui("DISPLAY_CUS_TABLE").setVisible(true);
			
			ui('DISPLAY_CUS_TABLE_LABEL').setText("List (" + data.length + ")");
			//fn_clear_table_sorter("DISPLAY_BP_TABLE");
			
		}		
	};

	const listingBp = {
		_getData : function(data){
			ui("CUS_LISTING_TABLE").unbindRows();
			
			var lt_model = new sap.ui.model.json.JSONModel();
				lt_model.setSizeLimit(data.length);
				lt_model.setData(data);
				
			ui('CUS_LISTING_TABLE').setModel(lt_model).bindRows("/");
			ui("CUS_LISTING_TABLE").setVisible(true);
			
			ui('CUS_LISTING_LABEL').setText("Customer Information (" + data.length + ")");
		}
	}

	//ANG PAG CONFIRM NGA FORM
	let lv_dialog_save = new sap.m.Dialog("CUS_SAVE_DIALOG",{
		title: "Confirmation",
		beginButton: new sap.m.Button({
			text:"Yes",
			type:"Accept",
			icon:"sap-icon://accept",
			press:function(oEvt){
				if(screenMode._mode == "create"){
					createCus();
				}else{
					bpDataOrganizer._updateById(screenMode._id);
				}
				oEvt.getSource().getParent().close();
			}
		}),
		endButton:new sap.m.Button({
			text:"Cancel",
			type:"Reject",
			icon:"sap-icon://decline",
			press:function(oEvt){
			oEvt.getSource().getParent().close();
			}
		}),
		content:[
			new sap.m.HBox({
				items:[
				new sap.m.Label({text:"Confirm to save changes?"})
				]
			})
		]
	}).addStyleClass('sapUiSizeCompact');



</script>
