
			function FindReader_onclick()
			{
				var str;
				str = SynCardOcx1.FindReader();
				if (str > 0)
				{
					if(str>1000)
					{
						str =document.all['S1'].value+ "读卡器连接在USB " + str+"\r\n" ;
					}
					else
					{
						str =document.all['S1'].value+ "读卡器连接在COM " + str+"\r\n" ;
					}
				}
				else
				{
					str =document.all['S1'].value+ "没有找到读卡器\r\n";
				}
				document.all['S1'].value=str;

			}
			function ReadSAMID_onclick()
			{
				var str=SynCardOcx1.GetSAMID();
				document.all['S1'].value=document.all['S1'].value+"读卡器SAMID为:"+str+"\r\n";
			}
			function Clear_onclick()
			{
				document.all['S1'].value="";
			}
			function ReadCard_onclick()
			{
				var nRet;
				SynCardOcx1.SetReadType(0);
				SynCardOcx1.SetSexType (1);
				SynCardOcx1.SetNationType(2);
				nRet = SynCardOcx1.ReadCardMsg();
				if(nRet==0)
				{

					//var str="C:\\";
					document.all['address'].value = SynCardOcx1.Address;
					$("#sex").text(SynCardOcx1.Sex);
					//$("#imginfo").html('<img src="'+SynCardOcx1.PhotoName+'" />');
					//document.all['S1'].value=document.all['S1'].value+"读卡成功\r\n";	
					//document.all['S1'].value=document.all['S1'].value+"姓名:"+SynCardOcx1.NameA +"\r\n";
					//document.all['S1'].value=document.all['S1'].value+"性别:"+SynCardOcx1.Sex +"\r\n";
					//document.all['S1'].value=document.all['S1'].value+"民族:"+SynCardOcx1.Nation +"\r\n";
					//document.all['S1'].value=document.all['S1'].value+"出生日期:"+SynCardOcx1.Born +"\r\n";
					//document.all['S1'].value=document.all['S1'].value+"地址:"+SynCardOcx1.Address +"\r\n";
					//document.all['S1'].value=document.all['S1'].value+"身份证号:"+SynCardOcx1.CardNo +"\r\n";
					//document.all['S1'].value=document.all['S1'].value+"有效期开始:"+SynCardOcx1.UserLifeB +"\r\n";
					//document.all['S1'].value=document.all['S1'].value+"有效期结束:"+SynCardOcx1.UserLifeE +"\r\n";
					//document.all['S1'].value=document.all['S1'].value+"发证机关:"+SynCardOcx1.Police +"\r\n";
					//document.all['S1'].value=document.all['S1'].value+"照片文件名:"+SynCardOcx1.PhotoName +"\r\n";
				}
			}
			function ReadCardAuto_onclick()
			{
				SynCardOcx1.SetloopTime(1000);
				SynCardOcx1.SetReadType(1);
			}

			function PhotoPath_onclick()
			{
				var str="";
				SynCardOcx1.SetPhotoPath(0,str);
				document.all['S1'].value=document.all['S1'].value+"照片保存路径设置为C盘根目录\r\n";
			}
			function PhotoPath2_onclick()
			{
				var str="";
				SynCardOcx1.SetPhotoPath(1,str);
				document.all['S1'].value=document.all['S1'].value+"照片保存路径设置为当前目录\r\n";
			}
			function PhotoPath3_onclick()
			{
				var str="D:\\Photo";
				var nRet;
				nRet= SynCardOcx1.SetPhotoPath(2,str);
				if(nRet == 0)
				{
					document.all['S1'].value=document.all['S1'].value+"照片保存路径设置为"+str+"\r\n";
				}
				else
				{
					document.all['S1'].value=document.all['S1'].value+"照片保存路径设置失败\r\n";  	
				}
			}
			function PhotoType_onclick()
			{
				var nRet;
				nRet=SynCardOcx1.SetPhotoType(0);
				if(nRet==0)
				{
					document.all['S1'].value=document.all['S1'].value+"照片保存格式设置为Bmp\r\n";
				}
			}
			function PhotoType2_onclick()
			{
				var nRet;
				nRet=SynCardOcx1.SetPhotoType(1);
				if(nRet==0)
				{
					document.all['S1'].value=document.all['S1'].value+"照片保存格式设置为Jpeg\r\n";
				}
			}
			function PhotoType3_onclick()
			{
				var nRet;
				nRet=SynCardOcx1.SetPhotoType(2);
				if(nRet==0)
				{
					document.all['S1'].value=document.all['S1'].value+"照片保存格式设置为Base64\r\n";
				}
			}
			function PhotoName_onclick()
			{
				var nRet;
				nRet=SynCardOcx1.SetPhotoName(0);
				if(nRet==0)
				{
					document.all['S1'].value=document.all['S1'].value+"照片保存文件名设置为tmp\r\n";
				}
			}
			function PhotoName2_onclick()
			{
				var nRet;
				nRet=SynCardOcx1.SetPhotoName(1);
				if(nRet==0)
				{
					document.all['S1'].value=document.all['S1'].value+"照片保存文件名设置为 姓名\r\n";
				}
			}
			function PhotoName3_onclick()
			{
				var nRet;
				nRet=SynCardOcx1.SetPhotoName(2);
				if(nRet==0)
				{
					document.all['S1'].value=document.all['S1'].value+"照片保存文件名设置为 身份证号\r\n";
				}
			}
			function PhotoName4_onclick()
			{
				var nRet;
				nRet=SynCardOcx1.SetPhotoName(3);
				if(nRet==0)
				{
					document.all['S1'].value=document.all['S1'].value+"照片保存文件名设置为 姓名_身份证号\r\n";
				}
			}
			function PhotoName5_onclick()
			{
				var str= SynCardOcx1.Base64Photo;
				document.all['S1'].value=document.all['S1'].value+str+"  \r\n";
			}


